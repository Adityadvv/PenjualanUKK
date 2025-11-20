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
        $orders = Penjualan::with(['pelanggan','details.product'])
                    ->orderBy('tanggal_penjualan','desc')->get();
        return view('kasir.listorder', compact('orders'));
    }

    // Proses pembayaran
public function bayar(Request $request, $id)
{
    try {
        DB::transaction(function() use ($request, $id) {
            $order = Penjualan::with('details.product', 'pelanggan')->findOrFail($id);

            $order->status_pembayaran = 'terbayar';
            $order->metode_pembayaran = $request->metode_pembayaran;
            $order->save();

            // Update stok
            foreach ($order->details as $detail) {
                $product = $detail->product;
                if($product){
                    $product->stok = max(0, $product->stok - $detail->jumlah_product);
                    $product->terjual += $detail->jumlah_product;
                    $product->save();
                }
            }

            // Kembalikan status meja jika dine_in
            if($order->pelanggan->tipe_pesanan === 'dine_in' && $order->pelanggan->nomor_meja){
                $meja = Meja::where('nomor_meja', $order->pelanggan->nomor_meja)->first();
                if($meja){
                    $meja->status = 'tersedia';
                    $meja->oleh = null;
                    $meja->save();
                }
            }
        });

        $order = Penjualan::with('details.product', 'pelanggan')->findOrFail($id);

        // Struk lebih rapi
        $strukHtml = '<div style="width:300px; font-family: monospace; padding:10px;">';
        $strukHtml .= '<h4 style="text-align:center;">✦ Struk Pembayaran ✦</h4>';
        $strukHtml .= '<p>Nama: '.$order->pelanggan->namapelanggan.'<br>';
        $strukHtml .= 'Tanggal: '.\Carbon\Carbon::parse($order->tanggal_penjualan)->format('d-m-Y H:i').'<br>';
        $strukHtml .= 'Tipe: '.ucfirst(str_replace('_',' ',$order->pelanggan->tipe_pesanan)).'</p>';
        $strukHtml .= '<hr>';
        $strukHtml .= '<table style="width:100%; border-collapse: collapse;">';
        $strukHtml .= '<thead><tr><th style="text-align:left;">Produk</th><th>Qty</th><th>Subtotal</th></tr></thead>';
        $strukHtml .= '<tbody>';
        foreach($order->details as $d){
            $strukHtml .= '<tr>';
            $strukHtml .= '<td>'.$d->product->nama_product.'</td>';
            $strukHtml .= '<td>'.$d->jumlah_product.'</td>';
            $strukHtml .= '<td>Rp '.number_format($d->subtotal,0,',','.').'</td>';
            $strukHtml .= '</tr>';
        }
        $strukHtml .= '</tbody>';
        $strukHtml .= '</table>';
        $strukHtml .= '<hr>';
        $strukHtml .= '<p style="text-align:right;"><strong>Total: Rp '.number_format($order->total_harga,0,',','.').'</strong></p>';
        $strukHtml .= '<p style="text-align:center;">✦ Terima Kasih ✦</p>';
        $strukHtml .= '</div>';

        return response()->json([
            'success' => true,
            'strukHtml' => $strukHtml
        ]);

    } catch (\Exception $e){
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

}