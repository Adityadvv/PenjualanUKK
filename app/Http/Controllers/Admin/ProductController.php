<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Tampilkan daftar produk
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::when($search, function($query, $search) {
            return $query->where('nama_product', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.product', compact('products', 'search'));
    }

    // Filter produk
    public function filter($category = null)
    {
        $products = $category ? Product::where('category', $category)->get() : Product::all();
        return response()->json($products);
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama_product' => 'required|string|max:255',
            'harga_satuan' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'category' => 'required',
        ]);

        $imagePath = null;
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('products', 'public');
        }

        Product::create([
            'nama_product' => $request->nama_product,
            'stok' => $request->stok,
            'harga_satuan' => $request->harga_satuan,
            'category' => $request->category,
            'gambar' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama_product' => 'required|string|max:255',
            'harga_satuan' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'category' => 'required',
        ]);

        $product = Product::findOrFail($id);
        $product->nama_product = $request->nama_product;
        $product->harga_satuan = $request->harga_satuan;
        $product->stok = $request->stok;
        $product->category = $request->category;

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            // Upload gambar baru
            $product->gambar = $request->file('gambar')->store('products', 'public');
        }

        $product->save();
        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    // Hapus produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
