@extends('layouts.admin')

@section('title', 'Data Pelanggan')

@section('content')
<div class="container-fluid pt-3">
    <h3>Data Pelanggan</h3>
    <hr>

    <div class="card shadow-sm rounded">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th scope="col" width="5%">No</th>
                        <th scope="col" width="10%">Nama Pelanggan</th>
                        <th scope="col" width="15%">Alamat</th>
                        <th scope="col" width="10%">No Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelanggans as $index => $pelanggan)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $pelanggan->namapelanggan }}</td>
                            <td>{{ $pelanggan->alamat ?? '-' }}</td>
                            <td>{{ $pelanggan->no_telepon ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data pelanggan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection