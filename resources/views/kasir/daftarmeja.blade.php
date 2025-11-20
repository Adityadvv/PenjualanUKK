@extends('layouts.kasir')

@section('title', 'Manage Meja')

@section('content')
<div class="container-fluid pt-3">
    <h3>🪑 Daftar Meja</h3>
    <hr>

    {{-- Tambah Meja Baru --}}
    <form action="{{ route('kasir.daftarmeja.store') }}" method="POST" class="mb-3 d-flex gap-2">
        @csrf
        <input type="number" style="height: 38px; width: 250px;" name="nomor_meja" class="form-control" placeholder="Nomor Meja" required>
        <button type="submit" class="btn btn-primary">Tambah Meja</button>
    </form>

    {{-- Tabel Meja --}}
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th style="text-align: center; width : 25%">Nomor Meja</th>
                        <th style="text-align: center; width: 35%">Status</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mejas as $meja)
                    <tr>
                        <td style="text-align: center;">{{ str_pad($meja->nomor_meja, 2, '0', STR_PAD_LEFT) }}</td>
                        <td style="text-align: center;">
                            @if($meja->status === 'tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-secondary">Dipakai</span>
                            @endif
                        </td>
                        <td>{{ $meja->oleh ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
