<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Supplier;

class InventoryController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil semua supplier beserta barang
        $suppliers = Supplier::with('barangs')
            ->when($search, function ($query, $search) {
                $query->where('nama_supplier', 'like', "%{$search}%")
                      ->orWhere('nama_pic', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Ambil semua barang beserta supplier
        $barangs = Barang::with('supplier')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.inventory', compact('suppliers', 'barangs', 'search'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'nama_pic' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'keterangan' => 'nullable|string|max:1000',
            'barang.nama_barang.*' => 'required|string|max:255',
            'barang.harga_per_kg.*' => 'required|numeric|min:0',
            'barang.qty.*' => 'required|numeric|min:0',
            'barang.detail_barang.*' => 'nullable|string|max:1000',
        ]);

        // Cek apakah supplier sudah ada (berdasarkan nama_supplier)
        $supplier = Supplier::firstOrCreate(
            ['nama_supplier' => $request->nama_supplier],
            [
                'nama_pic' => $request->nama_pic,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'keterangan' => $request->keterangan
            ]
        );

        // Simpan Barang terkait Supplier
        if ($request->has('barang')) {
            foreach ($request->barang['nama_barang'] as $key => $nama_barang) {
                $supplier->barangs()->create([
                    'nama_barang' => $nama_barang,
                    'harga_per_kg' => $request->barang['harga_per_kg'][$key],
                    'qty' => $request->barang['qty'][$key],
                    'detail_barang' => $request->barang['detail_barang'][$key] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Supplier dan Barang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'nama_pic' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'keterangan' => 'nullable|string|max:1000',
            'barang.nama_barang.*' => 'required|string|max:255',
            'barang.harga_per_kg.*' => 'required|numeric|min:0',
            'barang.qty.*' => 'required|numeric|min:0',
            'barang.detail_barang.*' => 'nullable|string|max:1000',
        ]);

        // Cari supplier yang akan diupdate
        $supplier = Supplier::findOrFail($id);

        // Update data supplier
        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'nama_pic' => $request->nama_pic,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'keterangan' => $request->keterangan,
        ]);

        // Hapus semua barang lama milik supplier
        $supplier->barangs()->delete();

        // Tambahkan barang baru
        if ($request->has('barang')) {
            foreach ($request->barang['nama_barang'] as $key => $nama_barang) {
                $supplier->barangs()->create([
                    'nama_barang' => $nama_barang,
                    'harga_per_kg' => $request->barang['harga_per_kg'][$key],
                    'qty' => $request->barang['qty'][$key],
                    'detail_barang' => $request->barang['detail_barang'][$key] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Supplier dan Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Hapus semua barang yang terhubung ke supplier
        $supplier->barangs()->delete();

        // Hapus supplier
        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier dan Barang berhasil dihapus.');
    }
}