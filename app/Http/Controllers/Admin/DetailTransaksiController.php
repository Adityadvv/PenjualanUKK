<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenjualanDetail;

class DetailTransaksiController extends Controller
{
     public function index()
    {
        // Ambil semua detail beserta relasi penjualan dan product
        $details = PenjualanDetail::with(['penjualan.pelanggan', 'product'])
                    ->orderBy('penjualan_id', 'asc')
                    ->get();

        return view('admin.detailtransaksi', compact('details'));
    }
}
