<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\PenjualanDetail;
use DB;

class ListOrderController extends Controller
{
    // Menampilkan semua order
    public function index()
    {
        // Ambil semua penjualan beserta data pelanggan
        $orders = Penjualan::with('pelanggan')->orderBy('tanggal_penjualan', 'desc')->get();
        return view('kasir.listorder', compact('orders'));
    }

    // Menampilkan detail order / form bayar
    public function show($id)
    {
        $order = Penjualan::with(['pelanggan', 'details'])->findOrFail($id);
        return view('kasir.listorder.show', compact('order'));
    }

    // Update data order saat bayar
    public function bayar(Request $request, $id)
    {
        $order = Penjualan::findOrFail($id);

        $order->status_pembayaran = 'terbayar';
        $order->metode_pembayaran = $request->metode_pembayaran;
        $order->save();

        return redirect()->route('kasir.listorder')->with('success', 'Order berhasil dibayar!');
    }
}