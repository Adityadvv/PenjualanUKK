<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiBarang;
use App\Models\Barang;
use App\Models\Supplier;

class TransaksiBarangController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiBarang::with(['barang', 'supplier'])->orderBy('created_at','desc')->paginate(20);
        $barangs = Barang::all();
        $suppliers = Supplier::all();
        return view('admin.inventorybarang.transaksibarang', compact('transaksis','barangs','suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            'qty' => 'required|numeric|min:0.01',
            'harga_per_kg' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string'
        ]);

        $total = $request->qty * $request->harga_per_kg;

        $transaksi = TransaksiBarang::create([
            'barang_id' => $request->barang_id,
            'supplier_id' => $request->supplier_id,
            'tipe_transaksi' => $request->tipe_transaksi,
            'qty' => $request->qty,
            'harga_per_kg' => $request->harga_per_kg,
            'total_harga' => $total,
            'keterangan' => $request->keterangan
        ]);

        // Update stok barang otomatis
        $barang = $transaksi->barang;
        if($transaksi->tipe_transaksi === 'masuk'){
            $barang->qty += $transaksi->qty;
        } else {
            $barang->qty = max(0, $barang->qty - $transaksi->qty);
        }
        $barang->save();

        return redirect()->back()->with('success','Transaksi barang berhasil disimpan!');
    }
}