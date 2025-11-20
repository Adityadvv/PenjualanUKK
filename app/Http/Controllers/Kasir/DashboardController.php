<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pelanggan;
use App\Models\Meja;

class DashboardController extends Controller
{
    public function index()
    {
        // Semua produk
        $products = Product::orderBy('category')->get();

        // Kategori tetap muncul meskipun belum ada produk
        $categories = ['Burger', 'Pizza', 'Minuman', 'Snack'];

        // Ambil meja yang tersedia
        $mejas = Meja::where('status', 'tersedia')->orderBy('nomor_meja')->get();

        return view('kasir.dashboard', compact('products', 'categories', 'mejas'));
    }

        public function store(Request $request)
{
    $validated = $request->validate([
        'namapelanggan' => 'required|string|max:255',
        'alamat' => 'nullable|string',
        'no_telepon' => 'nullable|string|max:20',
        'tipe_pesanan' => 'required|string',
        'nomor_meja' => 'nullable|integer',
        'metode_pembayaran' => 'required|string',
        'order_data' => 'required|json'
    ]);

    DB::transaction(function() use ($validated) {
        // 1. Simpan pelanggan
        $pelanggan = Pelanggan::create([
            'namapelanggan' => $validated['namapelanggan'],
            'alamat' => $validated['alamat'] ?? '',
            'no_telepon' => $validated['no_telepon'] ?? '',
            'tipe_pesanan' => $validated['tipe_pesanan'],
            'nomor_meja' => $validated['nomor_meja'] ?? null,
        ]);

        // Jika dine-in, update status meja
    if($validated['tipe_pesanan'] === 'dine_in' && $validated['nomor_meja']){
        $meja = Meja::where('nomor_meja', $validated['nomor_meja'])->first();
        if($meja){
            $meja->status = 'dipakai';
            $meja->oleh = $validated['namapelanggan'];
            $meja->save();
        }
    }

        // 2. Hitung total harga
        $orderItems = json_decode($validated['order_data'], true);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['qty'], $orderItems));

        // 3. Simpan penjualan beserta metode pembayaran
        $penjualan = $pelanggan->penjualans()->create([
            'total_harga' => $total,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'belum_terbayar',
        ]);

        // 4. Simpan detail penjualan & kurangi stok
        foreach ($orderItems as $item) {
            $penjualan->details()->create([
                'product_id' => $item['id'],
                'jumlah_product' => $item['qty'],
                'subtotal' => $item['price'] * $item['qty'],
            ]);

            // 5. Kurangi stok produk
            $product = Product::find($item['id']);
            if($product){
                $product->stok = max(0, $product->stok - $item['qty']);
                $product->save();
            }
        }
    });

    return redirect()->back()->with('success', 'Order berhasil disimpan!');
}
}