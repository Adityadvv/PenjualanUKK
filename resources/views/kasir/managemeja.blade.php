@extends('layouts.kasir')

@section('title', 'Manage Meja')

@section('content')
<div class="container-fluid pt-3">
    <h3>🪑 Manage Meja</h3>
    <hr>

    {{-- Tambah Meja Baru --}}
    <form action="{{ route('kasir.managemeja.store') }}" method="POST" class="mb-3 d-flex gap-2">
        @csrf
        <input type="number" name="nomor_meja" class="form-control" placeholder="Nomor Meja" required>
        <button type="submit" class="btn btn-primary">Tambah Meja</button>
    </form>

    {{-- Tabel Meja --}}
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nomor Meja</th>
                        <th>Status</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mejas as $meja)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $meja->nomor_meja }}</td>
                        <td>
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
