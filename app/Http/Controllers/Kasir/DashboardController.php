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
            'no_telepon' => 'nullable|string|max:20'
        ]);

        Pelanggan::create($validated);

        return redirect()->back()->with('success', 'Data pelanggan berhasil disimpan!');
    }
}
