@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container-fluid pt-3">
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <div class="d-flex mb-3 justify-content-between align-items-center">
                <h5 class="mb-0">📋 Detail Transaksi</h5>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari kode penjualan, pelanggan atau produk..." style="max-width: 300px;">
            </div>

            <table class="table table-bordered table-striped" id="detailTransaksiTable">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Penjualan ID</th>
                        <th>Nama Pelanggan</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Tanggal Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->penjualan->kode_penjualan }}</td>
                        <td>{{ $item->penjualan->pelanggan->namapelanggan }}</td>
                        <td>{{ $item->product->nama_product }}</td>
                        <td>{{ $item->jumlah_product }}</td>
                        <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                        <td>{{ $item->penjualan->tanggal_penjualan->format('d-m-Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Filter search sederhana
    document.getElementById('searchInput').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('#detailTransaksiTable tbody tr').forEach(function(row){
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection