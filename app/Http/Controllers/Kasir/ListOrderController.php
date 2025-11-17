<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Meja;

class ListOrderController extends Controller
{
    public function index()
    {
        $orders = Penjualan::with('pelanggan')->orderBy('tanggal_penjualan', 'desc')->get();
        return view('kasir.listorder', compact('orders'));
    }

    public function show($id)
    {
        $order = Penjualan::with(['pelanggan', 'details'])->findOrFail($id);
        return view('kasir.listorder.show', compact('order'));
    }

    public function bayar(Request $request, $id)
    {
        DB::transaction(function() use ($request, $id) {
            $order = Penjualan::with('details', 'pelanggan')->findOrFail($id);

            // Update status pembayaran
            $order->status_pembayaran = 'terbayar';
            $order->metode_pembayaran = $request->metode_pembayaran;
            $order->save();

            // Kurangi stok produk
            foreach ($order->details as $detail) {
                $product = Product::find($detail->product_id);
                if($product){
                    $product->stok = max(0, $product->stok - $detail->jumlah_product);
                    $product->save();
                }
            }

            // Jika dine-in, ubah status meja menjadi tersedia
            if($order->pelanggan->tipe_pesanan === 'dine_in' && $order->pelanggan->nomor_meja){
                $meja = Meja::where('nomor_meja', $order->pelanggan->nomor_meja)->first();
                if($meja){
                    $meja->status = 'tersedia';
                    $meja->oleh = null;
                    $meja->save();
                }
            }
        });

        return redirect()->route('kasir.listorder')->with('success', 'Order berhasil dibayar!');
    }
}
