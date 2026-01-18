@extends('layouts.admin')

@section('title', 'Manajemen Barang')

@section('content')
<div class="container-fluid pt-3">
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <div class="d-flex mb-3 justify-content-between align-items-center">
                <h5 class="mb-0">📋 Manajemen Barang & Supplier</h5>
            </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs" id="barangSupplierTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" style="width: 110px; text-align: center;" id="barang-tab" data-toggle="tab" href="#barang" role="tab">Barang</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" style="width: 110px; text-align: center;" id="supplier-tab" data-toggle="tab" href="#supplier" role="tab">Supplier</a>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content mt-3" id="barangSupplierTabContent">

        {{-- Barang Tab --}}
        <div class="tab-pane fade show active" id="barang" role="tabpanel">
            <div class="card shadow-sm rounded">
                <div class="card-body">
                    <div class="d-flex justify-content-end align-items-center mb-3" style="gap: 10px;">
              <label for="searchInput" class="mb-0" style="white-space: nowrap;">Cari Barang:</label>
              <input type="text" id="searchInput" class="form-control" placeholder="Masukkan nama barang..." style="max-width: 300px;">
            </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="table-layout: fixed;">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width: 5%; text-align: center;">No</th>
                                    <th style="width: 20%;">Nama Barang</th>
                                    <th style="width: 13%; text-align: center;">Harga / kg</th>
                                    <th style="width: 10%; text-align: center;">Qty</th>
                                    <th style="width: 20%;">Detail</th>
                                    <th style="width: 18%;">Supplier</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse($barangs as $barang)
                                <tr>
                                    <td style="text-align: center;">{{ $no }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td style="text-align: center;">Rp{{ number_format($barang->harga_per_kg,0,',','.') }}</td>
                                    <td style="text-align: center;">{{ $barang->qty }}Kg</td>
                                    <td>{{ $barang->detail_barang }}</td>
                                    <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailBarangModal{{ $barang->id }}">Detail</button>
                                    </td>
                                </tr>
                                @php $no++; @endphp
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data barang.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $barangs->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Supplier Tab --}}
        <div class="tab-pane fade" id="supplier" role="tabpanel">
            <div class="card shadow-sm rounded">
                <div class="card-body">
                    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createSupplierModal">+ Tambah Supplier</button>
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered" style="min-width: 1200px; table-layout: fixed;">
                            <thead class="table-primary">
                                <tr>
                                    <th style="width: 5%; text-align:center;">No</th>
                                    <th style="width: 15%;">Nama Supplier</th>
                                    <th style="width: 15%;">Nama PIC</th>
                                    <th style="width: 16%;">Email</th>
                                    <th style="width: 13%; text-align:center;">No. Telp</th>
                                    <th style="width: 15%;">Alamat</th>
                                    <th style="width: 13%;">Keterangan</th>
                                    <th style="width: 15%; text-align:center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $noSupplier = 1; @endphp
                                @forelse($suppliers as $supplier)
                                <tr>
                                    <td style="text-align:center;">{{ $noSupplier }}</td>
                                    <td>{{ $supplier->nama_supplier }}</td>
                                    <td>{{ $supplier->nama_pic }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td style="text-align:center;">{{ $supplier->no_telp }}</td>
                                    <td>{{ $supplier->alamat }}</td>
                                    <td>{{ $supplier->keterangan }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" style="color: white;" data-target="#editSupplierModal{{ $supplier->id }}">Edit</button>
                                        <form action="{{ route('inventory.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger deleteBtn">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @php $noSupplier++; @endphp
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data supplier.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Barang --}}
@foreach($barangs as $barang)
<div class="modal fade" id="detailBarangModal{{ $barang->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Nama Barang:</strong> {{ $barang->nama_barang }}</p>
                <p><strong>Harga / kg:</strong> Rp{{ number_format($barang->harga_per_kg,0,',','.') }}</p>
                <p><strong>Qty:</strong> {{ $barang->qty }}Kg </p>
                <p><strong>Detail:</strong> {{ $barang->detail_barang }}</p>
                <hr>
                <p><strong>Supplier:</strong> {{ $barang->supplier->nama_supplier ?? '-' }}</p>
                <p><strong>PIC:</strong> {{ $barang->supplier->nama_pic ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $barang->supplier->email ?? '-' }}</p>
                <p><strong >No. Telp:</strong> {{ $barang->supplier->no_telp ?? '-' }}</p>
                <p><strong>Alamat:</strong> {{ $barang->supplier->alamat ?? '-' }}</p>
                <p><strong>Keterangan:</strong> {{ $barang->supplier->keterangan ?? '-' }}</p>
                <hr>
                <p><strong>Di-Input:</strong> {{ $barang->supplier?->created_at?->timezone('Asia/Jakarta')->format('d F Y H:i:s') ?? '-' }}</p>
                <p><strong>Diubah:</strong> {{ $barang->supplier?->updated_at?->timezone('Asia/Jakarta')->format('d F Y H:i:s') ?? '-' }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- Modal Edit Supplier & Barang --}}
@foreach($suppliers as $supplier)
<div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form  id="editSupplierForm{{ $supplier->id }}" action="{{ route('inventory.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning ">
                    <h5 class="modal-title text-white">Edit Supplier & Barang</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{-- Data Supplier --}}
                    <div class="form-group">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" value="{{ $supplier->nama_supplier }}" required>
                    </div>
                    <div class="form-group">
                        <label>Nama PIC</label>
                        <input type="text" name="nama_pic" class="form-control" value="{{ $supplier->nama_pic }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control" value="{{ $supplier->no_telp }}">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control">{{ $supplier->alamat }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control">{{ $supplier->keterangan }}</textarea>
                    </div>
                    <hr>
                    {{-- Barang --}}
                    <div class="barang-wrapper">
                        @foreach($supplier->barangs as $barang)
                        <div class="barang-item mb-2">
                            <label>Informasi Barang</label>
                            <div class="row">
                                <div class="col"><input type="text" name="barang[nama_barang][]" class="form-control" value="{{ $barang->nama_barang }}" required></div>
                                <div class="col"><input type="number" name="barang[harga_per_kg][]" class="form-control" value="{{ $barang->harga_per_kg }}" required></div>
                                <div class="col"><input type="number" name="barang[qty][]" class="form-control" value="{{ $barang->qty }}" required></div>
                                <div class="col"><input type="text" name="barang[detail_barang][]" class="form-control" value="{{ $barang->detail_barang }}"></div>
                                <div class="col-auto"><button type="button" class="btn btn-danger btn-sm remove-barang">X</button></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary btn-sm addBarangBtn">+ Tambah Barang</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info updateSupplierBtn" data-id="{{ $supplier->id }}">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Modal Tambah Supplier & Barang --}}
<div class="modal fade" id="createSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="supplierForm" action="{{ route('inventory.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Tambah Supplier & Barang</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Supplier<span class="text-danger">*</span></label>
                        <input type="text" name="nama_supplier" class="form-control" placeholder="Nama Supplier" required>
                    </div>
                    <div class="form-group">
                        <label>Nama PIC<span class="text-danger">*</span></label>
                        <input type="text" name="nama_pic" class="form-control" placeholder="Nama PIC">
                    </div>
                    <div class="form-group">
                        <label>Email<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label>No. Telepon<span class="text-danger">*</span></label>
                        <input type="text" name="no_telp" class="form-control" placeholder="No Telepon">
                    </div>
                    <div class="form-group">
                        <label>Alamat<span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control" placeholder="Alamat"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Keterangan<span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                    </div>
                    <hr>
                    <h5>Barang Supplier<span class="text-danger">*</span></h5>
                    <div class="barang-wrapper">
                        <div class="barang-item mb-2">
                            <div class="row">
                                <div class="col"><input type="text" name="barang[nama_barang][]" class="form-control" placeholder="Nama Barang" required></div>
                                <div class="col"><input type="number" name="barang[harga_per_kg][]" class="form-control" placeholder="Harga/kg" required></div>
                                <div class="col"><input type="number" name="barang[qty][]" class="form-control" placeholder="Qty ( Kg )" required></div>
                                <div class="col"><input type="text" name="barang[detail_barang][]" class="form-control" placeholder="Detail Barang"></div>
                                <div class="col-auto"><button type="button" class="btn btn-danger btn-sm remove-barang">X</button></div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="addBarangBtn">+ Tambah Barang</button>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitSupplierBtn" class="btn btn-success">Simpan</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
//add supplier barang
document.getElementById('submitSupplierBtn').addEventListener('click', function () {
    var form = document.getElementById('supplierForm');

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
        text: 'Apakah Anda yakin ingin menyimpan data ini?',
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
            text: 'Data berhasil disimpan',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            form.submit(); // submit form setelah sweatalert
        });
    });
});

// Edit
$(document).on('click', '.updateSupplierBtn', function(){
    let id = $(this).data('id');
    let form = document.getElementById('editSupplierForm' + id);

    if(!form.checkValidity()){ 
        form.reportValidity(); 
        return; 
    }
        $(form).closest('.modal').modal('hide');

    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin mengubah data ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Batal'
    }).then((result)=>{
        if(!result.isConfirmed) return;

        // Tampilkan alert sukses
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil diupdate',
            timer: 1200,
            showConfirmButton: false
        }).then(()=>{ 
            form.submit(); 
        });
    });
});

// Hapus
$(document).on('click', '.deleteBtn', function (e) {
    e.preventDefault(); 
    let form = $(this).closest('form');

    Swal.fire({
        title: 'Hapus Data Supplier Barang?',
        text: 'Data yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d'

    }).then((result) => {

        // batal - stop
        if (!result.isConfirmed) return;

        // ya - success
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil dihapus',
            timer: 1200,
            showConfirmButton: false
        }).then(() => {
            form.submit(); //submit after success
        });
    });
 });

 $(document).ready(function() {
    // Fade alert
    $(".alert").fadeTo(3000, 500).slideUp(500, function(){ $(this).remove(); });

    // search barang
    $("#searchBarang").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#barang tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Tambah barang dinamis (modal tambah & edit)
    $(document).on("click", "#addBarangBtn, .addBarangBtn", function() {
        var wrapper = $(this).closest(".modal-body").find(".barang-wrapper");
        var html = `<div class="barang-item mb-2">
            <div class="row">
                <div class="col"><input type="text" name="barang[nama_barang][]" class="form-control" placeholder="Nama Barang" required></div>
                <div class="col"><input type="number" name="barang[harga_per_kg][]" class="form-control" placeholder="Harga/kg" required></div>
                <div class="col"><input type="number" name="barang[qty][]" class="form-control" placeholder="Qty" required></div>
                <div class="col"><input type="text" name="barang[detail_barang][]" class="form-control" placeholder="Detail Barang"></div>
                <div class="col-auto"><button type="button" class="btn btn-danger btn-sm remove-barang">X</button></div>
            </div>
        </div>`;
        wrapper.append(html);
    });

    // Hapus barang dinamis
    $(document).on("click", ".remove-barang", function() {
        $(this).closest(".barang-item").remove();
    });
});
</script>
@endsection