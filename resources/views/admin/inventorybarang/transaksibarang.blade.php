@extends('layouts.admin')

@section('title', 'Monitoring Transaksi Barang')

@section('content')
<div class="container-fluid pt-3">
    <h3>Monitoring Transaksi Barang</h3>
    <hr>

    <div class="card shadow-sm rounded">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th>Nama Barang</th>
                            <th>Supplier</th>
                            <th>Harga / Kg</th>
                            <th>Qty (Kg)</th>
                            <th>Total Pengeluaran</th>
                            <th style="text-align: center;" >Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @forelse($transaksis as $i => $transaksi)
                            <tr>
                                <td style="text-align: center;">{{ $i + 1 }}</td>
                                <td>{{ $transaksi->barang->nama_barang }}</td>
                                <td>{{ $transaksi->supplier->nama_supplier ?? '-' }}</td>
                                <td>Rp{{ number_format($transaksi->harga_per_kg,0,',','.') }}</td>
                                <td>{{ $transaksi->qty }} Kg</td>
                                <td>Rp{{ number_format($transaksi->total_harga,0,',','.') }}</td>
                                <td>{{ $transaksi->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @php
                            $grandTotal = $transaksis->where('tipe_transaksi', 'masuk')->sum('total_harga');
                            @endphp
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($transaksis->count())
                        <tfoot>
                            <tr class="table-secondary">
                                <th colspan="5" class="text-right">Total Pengeluaran</th>
                                <th colspan="2">Rp{{ number_format($grandTotal,0,',','.') }}</th>
                            </tr>
                        </tfoot>
                    @endif
                </table>

                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection