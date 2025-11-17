@extends('layouts.kasir')

@section('title', 'List Order')

@section('content')
<div class="container-fluid pt-3">
    <h3>📋 List Order Customer</h3>
    <hr>

    @if(session('success'))
        <div id="successAlert" class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm rounded">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->pelanggan->namapelanggan }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->tanggal_penjualan)->format('d-m-Y H:i') }}</td>
                        <td>Rp {{ number_format($order->total_harga,0,',','.') }}</td>
                        <td class="text-center">
                            @if($order->status_pembayaran === 'belum_terbayar')
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#bayarModal{{ $order->penjualan_id }}">Bayar</button>
                            @else
                                <span class="badge badge-info">Terbayar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada order</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Bayar (diletakkan di luar table) --}}
@foreach($orders as $order)
<div class="modal fade" id="bayarModal{{ $order->penjualan_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('kasir.listorder.bayar', $order->penjualan_id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bayar Order - {{ $order->pelanggan->namapelanggan }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    {{-- Data Pelanggan --}}
                    <h6>Data Pelanggan</h6>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">Nama</div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="{{ $order->pelanggan->namapelanggan }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">Alamat</div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="{{ $order->pelanggan->alamat }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">No Telepon</div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="{{ $order->pelanggan->no_telepon }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">Tipe Pesanan</div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="{{ ucfirst(str_replace('_',' ', $order->pelanggan->tipe_pesanan)) }}" readonly>
                        </div>
                    </div>
                    
                    @if($order->pelanggan->tipe_pesanan === 'dine_in')
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">Nomor Meja</div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="{{ $order->pelanggan->nomor_meja ?? '-' }}" readonly>
                        </div>
                    </div>
                    @endif

                    <hr>
                    {{-- Menu yang Dipesan --}}
                    <h6>Menu yang Dipesan</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalItem = 0; @endphp
                            @foreach($order->details as $detail)
                            <tr>
                                <td>{{ $detail->product->nama_product }}</td>
                                <td>{{ $detail->jumlah_product }}</td>
                                <td>Rp {{ number_format($detail->subtotal,0,',','.') }}</td>
                            </tr>
                            @php $totalItem += $detail->jumlah_product; @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total Item:</strong>
                        <span>{{ $totalItem }} Item</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total Harga:</strong>
                        <strong>Rp {{ number_format($order->total_harga,0,',','.') }}</strong>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">Metode Pembayaran</div>
                        <div class="col-md-8">
                            <select name="metode_pembayaran" class="form-control" required>
                                <option value="cash" {{ ($order->metode_pembayaran ?? 'cash') === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="qris" {{ ($order->metode_pembayaran ?? 'cash') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Bayar & Buat Struk</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertBox = document.getElementById('successAlert');
    if(alertBox){
        setTimeout(() => {
            // Hilangkan alert dengan fade out
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = 0;
            setTimeout(() => alertBox.remove(), 500);
        }, 3000); // 3 detik
    }
});
</script>
@endsection