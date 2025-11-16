<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pelanggan;

class DashboardController extends Controller
{
    public function index()
    {
        // Semua produk
        $products = Product::orderBy('category')->get();

        // Kategori tetap muncul meskipun belum ada produk
        $categories = ['Burger', 'Pizza', 'Minuman', 'Snack'];

        return view('kasir.dashboard', compact('products', 'categories'));
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'namapelanggan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'tipe_pesanan' => 'required|string',
            'nomor_meja' => 'nullable|integer',
            'order_data' => 'required|json'
        ]);

        \DB::transaction(function() use ($validated) {
            // 1. Simpan pelanggan
            $pelanggan = Pelanggan::create([
                'namapelanggan' => $validated['namapelanggan'],
                'alamat' => $validated['alamat'] ?? '',
                'no_telepon' => $validated['no_telepon'] ?? '',
                'tipe_pesanan' => $validated['tipe_pesanan'],
                'nomor_meja' => $validated['nomor_meja'] ?? null,
            ]);

            // 2. Simpan penjualan
            $orderItems = json_decode($validated['order_data'], true);
            $total = array_sum(array_map(fn($item) => $item['price'] * $item['qty'], $orderItems));

            $penjualan = $pelanggan->penjualans()->create([
                'total_harga' => $total,
            ]);

            // 3. Simpan detail penjualan
            foreach ($orderItems as $item) {
                $penjualan->details()->create([
                    'product_id' => $item['id'],
                    'jumlah_product' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);
            }
        });

        return redirect()->back()->with('success', 'Order berhasil disimpan!');
    }
}
