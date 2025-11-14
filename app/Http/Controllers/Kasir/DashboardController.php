<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua produk, bisa filter per kategori nanti
        $products = Product::orderBy('category')->get();

        // Ambil kategori unik untuk tombol filter
        $categories = Product::select('category')->distinct()->pluck('category');

        return view('kasir.dashboard', compact('products', 'categories'));
    }
}
