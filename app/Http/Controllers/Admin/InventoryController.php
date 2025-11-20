<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\TransaksiBarang;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $suppliers = Supplier::with('barangs')
            ->when($search, function ($query, $search) {
                $query->where('nama_supplier', 'like', "%{$search}%")
                      ->orWhere('nama_pic', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $barangs = Barang::with('supplier')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.inventorybarang.inventory', compact('suppliers', 'barangs', 'search'));
    }

    public function store(Request $request)
    {
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

        $supplier = Supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'nama_pic' => $request->nama_pic,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'keterangan' => $request->keterangan
        ]);

        if ($request->has('barang')) {
            foreach ($request->barang['nama_barang'] as $key => $nama_barang) {
                $barang = $supplier->barangs()->create([
                    'nama_barang' => $nama_barang,
                    'harga_per_kg' => $request->barang['harga_per_kg'][$key],
                    'qty' => $request->barang['qty'][$key],
                    'detail_barang' => $request->barang['detail_barang'][$key] ?? null,
                ]);

                TransaksiBarang::create([
                    'barang_id' => $barang->id,
                    'supplier_id' => $supplier->id,
                    'tipe_transaksi' => 'masuk',
                    'qty' => $barang->qty,
                    'harga_per_kg' => $barang->harga_per_kg,
                    'total_harga' => $barang->qty * $barang->harga_per_kg,
                    'keterangan' => 'Stok awal',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Supplier dan Barang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
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

        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'nama_pic' => $request->nama_pic,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'keterangan' => $request->keterangan,
        ]);

        // Hapus barang lama + transaksi terkait
        foreach($supplier->barangs as $oldBarang){
            TransaksiBarang::where('barang_id', $oldBarang->id)->delete();
        }
        $supplier->barangs()->delete();

        // Tambah barang baru + transaksi masuk
        if ($request->has('barang')) {
            foreach ($request->barang['nama_barang'] as $key => $nama_barang) {
                $barang = $supplier->barangs()->create([
                    'nama_barang' => $nama_barang,
                    'harga_per_kg' => $request->barang['harga_per_kg'][$key],
                    'qty' => $request->barang['qty'][$key],
                    'detail_barang' => $request->barang['detail_barang'][$key] ?? null,
                ]);

                TransaksiBarang::create([
                    'barang_id' => $barang->id,
                    'supplier_id' => $supplier->id,
                    'tipe_transaksi' => 'masuk',
                    'qty' => $barang->qty,
                    'harga_per_kg' => $barang->harga_per_kg,
                    'total_harga' => $barang->qty * $barang->harga_per_kg,
                    'keterangan' => 'Penambahan stok dari update inventory',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Supplier dan Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        foreach($supplier->barangs as $oldBarang){
            TransaksiBarang::where('barang_id', $oldBarang->id)->delete();
        }
        $supplier->barangs()->delete();
        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier dan Barang berhasil dihapus.');
    }
}