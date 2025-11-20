<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meja;

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::orderBy('nomor_meja')->get();
        return view('kasir.daftarmeja', compact('mejas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|integer|unique:mejas,nomor_meja',
        ]);

        Meja::create([
            'nomor_meja' => $request->nomor_meja,
            'status' => 'tersedia',
        ]);

        return redirect()->back()->with('success', 'Meja baru berhasil ditambahkan!');
    }
}