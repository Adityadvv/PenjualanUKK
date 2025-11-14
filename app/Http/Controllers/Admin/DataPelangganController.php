<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pelanggan;

class DataPelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::orderBy('created_at', 'desc')->get();
        return view('admin.datapelanggan', compact('pelanggans'));
    }
}
