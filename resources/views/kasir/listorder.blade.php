@extends('layouts.kasir')

@section('title', 'List Order')

@section('content')
<div class="container-fluid pt-3">
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <div class="d-flex mb-3 justify-content-between align-items-center">
                <h5 class="mb-0">📋 List Order Customer</h5>
            </div>

    <div class="card shadow-sm rounded">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th style="text-align: center;">No</th>
                        <th>Nama Pelanggan</th>
                        <th style="text-align: center;">Tanggal Pemesanan</th>
                        <th>Total Harga</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $order->pelanggan->namapelanggan }}</td>
                        <td style="text-align: center;">{{ \Carbon\Carbon::parse($order->tanggal_penjualan)->format('d-m-Y H:i') }}</td>
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

{{-- Modal Bayar --}}
@foreach($orders as $order)
<div class="modal fade" id="bayarModal{{ $order->penjualan_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form onsubmit="return bayarDanTampilStruk(this, {{ $order->penjualan_id }})" method="POST" action="{{ route('kasir.listorder.bayar', $order->penjualan_id) }}">
                @csrf
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Bayar Order - {{ $order->pelanggan->namapelanggan }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    {{-- Data Pelanggan --}}
                    <h6>Data Pelanggan</h6>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">Nama</div>
                        <div class="col-md-8"><input type="text" class="form-control" value="{{ $order->pelanggan->namapelanggan }}" readonly></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">Alamat</div>
                        <div class="col-md-8"><input type="text" class="form-control" value="{{ $order->pelanggan->alamat }}" readonly></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">No Telepon</div>
                        <div class="col-md-8"><input type="text" class="form-control" value="{{ $order->pelanggan->no_telepon }}" readonly></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">Tipe Pesanan</div>
                        <div class="col-md-8"><input type="text" class="form-control" value="{{ ucfirst(str_replace('_',' ', $order->pelanggan->tipe_pesanan)) }}" readonly></div>
                    </div>
                    @if($order->pelanggan->tipe_pesanan === 'dine_in')
                    <div class="row mb-2">
                        <div class="col-md-4 fw-semibold">Nomor Meja</div>
                        <div class="col-md-8"><input type="text" class="form-control" value="{{ $order->pelanggan->nomor_meja ?? '-' }}" readonly></div>
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
                        <div class="col-md-8 d-flex align-items-center">
                            <select name="metode_pembayaran" class="form-control" onchange="toggleQris(this, {{ $order->penjualan_id }})" required>
                                <option value="cash" {{ ($order->metode_pembayaran ?? 'cash') === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="qris" {{ ($order->metode_pembayaran ?? 'cash') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                            <button type="button" class="btn btn-outline-secondary ml-2" id="btnQris{{ $order->penjualan_id }}" style="display:none;" onclick="toggleShowQris({{ $order->penjualan_id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div id="qrisContainer{{ $order->penjualan_id }}" style="display:none; text-align:center; margin-top:10px;">
                        <img src="{{ asset('storage/qrcode.png') }}" alt="QRIS" width="150">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Bayar & Tampilkan Struk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Modal Struk --}}
<div class="modal fade" id="strukModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resto Nusantara</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center" 
                 id="strukContent" 
                 style="font-family: monospace; text-align: center; min-height: 250px; white-space: pre-wrap;">
                <!-- Konten struk muncul di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" id="submitProductBtn"class="btn btn-primary" onclick="printStruk()">Cetak Struk</button>
                <button type="button" id="submitOrderBtn" class="btn btn-success">Selesai</button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const alertBox = document.getElementById('successAlert');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = 0;
            setTimeout(() => alertBox.remove(), 500);
        }, 3000);
    }

    //toggle qris 
    @foreach($orders as $order)
        const sel{{ $order->penjualan_id }} =
            document.querySelector('#bayarModal{{ $order->penjualan_id }} select[name="metode_pembayaran"]');

        if (sel{{ $order->penjualan_id }}) {
            toggleQris(sel{{ $order->penjualan_id }}, {{ $order->penjualan_id }});
        }
    @endforeach

    const kembaliBtn = document.getElementById('submitOrderBtn');
    if (kembaliBtn) {
        kembaliBtn.addEventListener('click', function () {

            $('#strukModal').modal('hide');

            // backdrop fix
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Transaksi selesai',
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: false
                }).then(() => {
                window.location.reload();
            });
            }, 300);
        });
    }

});

// Bayar & Tampilkan struk
function bayarDanTampilStruk(form, id) {
    const formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            document.getElementById('strukContent').innerHTML = data.strukHtml;
            $('#strukModal').modal('show');
            $('#bayarModal'+id).modal('hide');

            // FIX: hapus backdrop lama yang nutupin tombol X dan Kembali
            setTimeout(() => {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            }, 300);


            // Update badge Terbayar tanpa reload
            const row = document.querySelector('#bayarModal'+id).closest('tr');
            if(row){
                row.querySelector('td.text-center').innerHTML = '<span class="badge badge-info">Terbayar</span>';
            }
        } else {
            alert('Gagal menampilkan struk: ' + (data.message || 'Error'));
        }
    })
    .catch(err => { console.error(err); alert('Terjadi kesalahan saat memproses pembayaran.'); });
    return false;
}


// cetak struk
function printStruk() {
    var content = document.getElementById("strukContent").innerHTML;
    var myWindow = window.open('', 'Print Struk', 'height=600,width=400');

    myWindow.document.write('<html><head><title>Struk Pembayaran</title>');
    myWindow.document.write('<style>');
    myWindow.document.write(`
        body {
            font-family: monospace;
            text-align: center; 
            display: block; 
            margin: 20px;      
        }
    `);
    myWindow.document.write('</style>');
    myWindow.document.write('</head><body>');
    myWindow.document.write(content);
    myWindow.document.write('</body></html>');

    myWindow.document.close();
    myWindow.focus();
    myWindow.print();
    // Hapus myWindow.close(); agar modal tidak langsung close
}

// toggle QRIS button
function toggleQris(select, id){
    const btn = document.getElementById('btnQris'+id);
    const container = document.getElementById('qrisContainer'+id);
    if(select.value === 'qris'){
        btn.style.display = 'inline-block';
        container.style.display = 'none';
    } else {
        btn.style.display = 'none';
        container.style.display = 'none';
    }
}

// tampilkan / sembunyikan QRIS
function toggleShowQris(id){
    const container = document.getElementById('qrisContainer'+id);
    container.style.display = (container.style.display === 'block') ? 'none' : 'block';
}
</script>
@endsection