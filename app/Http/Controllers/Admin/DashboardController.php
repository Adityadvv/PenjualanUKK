<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunIni = Carbon::now()->year;
        $bulanIni = Carbon::now()->month;
        $bulanLalu = Carbon::now()->subMonth()->month;

        // ===== CARD =====
        $totalTransaksi = Penjualan::count(); 
        $totalPendapatan = Penjualan::sum('total_harga'); 
        $totalProduk = Product::count(); 
        $totalUser = User::count(); 

        // ===== KEUNTUNGAN BULANAN (HANYA PENDAPATAN) =====
        $pendapatanBulanIni = Penjualan::whereMonth('created_at', $bulanIni)
                                       ->whereYear('created_at', $tahunIni)
                                       ->sum('total_harga');

        $pendapatanBulanLalu = Penjualan::whereMonth('created_at', $bulanLalu)
                                        ->whereYear('created_at', $tahunIni)
                                        ->sum('total_harga');

        $persentaseKeuntungan = $pendapatanBulanLalu > 0
            ? round(($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu * 100, 2)
            : 0;

        // ===== GRAFIK =====
        $grafikBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $grafikBulanan[] = Penjualan::whereMonth('created_at', $i)
                                        ->whereYear('created_at', $tahunIni)
                                        ->sum('total_harga');
        }

        // Produk Laris/Tidak
        $produkLaris = Product::where('terjual', '>', 0)->count();
        $produkTidakLaris = Product::where('terjual', 0)->count();

        return view('admin.dashboard', compact(
            'totalTransaksi',
            'totalPendapatan',
            'totalProduk',
            'totalUser',
            'persentaseKeuntungan',
            'grafikBulanan',
            'produkLaris',
            'produkTidakLaris'
        ));
    }
}