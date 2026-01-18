@extends('layouts.admin')

@section('title', 'Data Pelanggan')

@section('content')
<div class="container-fluid pt-3">
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <div class="d-flex mb-3 justify-content-between align-items-center">
                <h5 class="mb-0">📋 Data Pelanggan</h5>
            </div>

    <div class="card shadow-sm rounded">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th scope="col" width="5%">No</th>
                        <th scope="col" style="text-align: left; width: 10%">Nama Pelanggan</th>
                        <th scope="col" style="text-align: left; width: 15%">Alamat</th>
                        <th scope="col" width="10%">No Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelanggans as $index => $pelanggan)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td style="text-align: left;">{{ $pelanggan->namapelanggan }}</td>
                            <td style="text-align: left;">{{ $pelanggan->alamat ?? '-' }}</td>
                            <td style="text-align: center;">{{ $pelanggan->no_telepon ?? '-' }}</td>
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
</div>
</div>
@endsection