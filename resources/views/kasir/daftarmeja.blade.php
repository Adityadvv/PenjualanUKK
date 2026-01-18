@extends('layouts.kasir')

@section('title', 'Manage Meja')

@section('content')
<div class="container-fluid pt-3">
<div class="card shadow-sm rounded">
    <div class="card-body">
        <div class="d-flex mb-3 justify-content-between align-items-center">
          <h5 class="mb-0">🪑 Daftar Meja</h5>
     </div>

    {{-- Tabel Meja --}}
    <div class="card shadow-sm rounded">
    <div class="card-body">
    <div class="d-flex justify-content-between mb-2">

    <form id="mejaForm" action="{{ route('kasir.daftarmeja.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Input Nomor Meja<span class="text-danger">*</span></label>
            <div class="d-flex align-items-center">
                <input type="number" name="nomor_meja" class="form-control mr-2" style="width: 250px;" placeholder="Nomor Meja" required>
                    <button type="button" id="submitMejaBtn" class="btn btn-success">+ Tambah Meja</button>
                 </div>
               </div>
            </form>
        </div>

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
</div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
//add meja
document.getElementById('submitMejaBtn').addEventListener('click', function () {
    var form = document.getElementById('mejaForm');

    // cek validasi
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // tutup modal 
    $(this).closest('.modal').modal('hide');

    // konfirmasi alert
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menyimpan nomor meja ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (!result.isConfirmed) return;

        // success alert
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Meja berhasil ditambahkan',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            form.submit(); // submit form setelah sweatalert
        });
    });
});
</script>
@endsection
