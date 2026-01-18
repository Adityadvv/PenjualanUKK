@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="container-fluid pt-3">
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <div class="d-flex mb-3 justify-content-between align-items-center">
                <h5 class="mb-0">📋 Manajemen Produk</h5>
            </div>

    <div class="card shadow-sm rounded">
        <div class="card-body">
            {{-- Tombol tambah + search --}}
            <div class="d-flex mb-3 justify-content-between">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createProductModal">
                    + Tambah Produk
                </button>
                
            </div>
        <div class="mb-3 d-flex justify-content-between align-items-end">
            <div class="mb-3 d-flex align-items-center" style="gap: 10px;">
                <label for="categoryFilter" class="mb-0" style="white-space: nowrap;">Kategori Produk:</label>
                <select id="categoryFilter" class="form-control">
                    <option value="">Semua Kategori</option>
                    <option value="Burger">Burger</option>
                    <option value="Pizza">Pizza</option>
                    <option value="Minuman">Minuman</option>
                    <option value="Snack">Snack</option>
                </select>
            </div>
            <div class="mb-3 d-flex align-items-center" style="gap: 10px;">
                <label for="searchInput" class="mb-0" style="white-space: nowrap;">Cari Produk:</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Masukkan nama produk..." style="max-width: 300px;">
            </div>
        </div>

            {{-- Tabel produk --}}
            <table class="table table-bordered table-striped table-hover" id="productTable">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th style="text-align: left;">Nama Produk</th>
                        <th style="text-align: left;">Harga Satuan</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td class="text-center">{{ $index + $products->firstItem() }}</td>
                            {{-- <td class="text-center">

                                <img src="{{ $product->gambar ? asset('storage/'.$product->gambar) : asset('images/no-image.png') }}" class="rounded" style="width:150px">
                            </td> --}}
                            <td class="text-center"> @if($product->gambar) <img src="{{ asset('storage/' . $product->gambar) }}" width="50" class="img-thumbnail"> @else <span class="text-muted">No Image</span> @endif </td>

                            <td>{{ $product->nama_product }}</td>
                            <td>Rp{{ number_format($product->harga_satuan,0,',','.') }}</td>
                            <td class="text-center">{{ $product->stok }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" style="color: white" data-target="#editProductModal{{ $product->product_id }}">Edit</button>
                                <button type="button" class="btn btn-sm btn-info detailProductBtn" data-id="{{ $product->product_id }}">Detail</button>                                
                                <form action="{{ route('product.destroy', $product->product_id) }}" method="POST" class="d-inline deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger deleteBtn">Hapus</button>
                            </form>
                        </td>
                    </tr>

                {{-- Modal Detail Produk --}}
                <div class="modal fade" id="detailProductModal{{ $product->product_id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title">Detail Produk</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body text-center">
                            <img src="{{ $product->gambar ? asset('storage/'.$product->gambar) : asset('images/no-image.png') }}" class="rounded" style="width:150px">
                            <p><strong>Nama:</strong> {{ $product->nama_product }}</p>
                            <p><strong>Harga:</strong> Rp{{ number_format($product->harga_satuan,0,',','.') }}</p>
                            <p><strong>Stok:</strong> {{ $product->stok }}</p>
                            <hr>
                            <p><strong>Ditambah:</strong> {{ $product->created_at->format('d F Y H:i:s') }}</p>
                            <p><strong>Diubah:</strong> {{ $product->updated_at->format('d F Y H:i:s') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Edit Produk --}}
            <div class="modal fade" id="editProductModal{{ $product->product_id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form id="editProductForm{{ $product->product_id }}" action="{{ route('product.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title text-white">Edit Produk</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Gambar</label>
                                    <input type="file" name="gambar" class="form-control">
                                        <img src="{{ $product->gambar ? asset('storage/'.$product->gambar) : asset('images/no-image.png') }}" class="rounded" style="width:150px">
                                </div>
                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama_product" class="form-control" value="{{ old('nama_product',$product->nama_product) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Harga Satuan</label>
                                    <input type="number" name="harga_satuan" class="form-control" value="{{ old('harga_satuan',$product->harga_satuan) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Stok</label>
                                    <input type="number" name="stok" class="form-control" value="{{ old('stok',$product->stok) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="category" class="form-control" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Burger" {{ $product->category == 'Burger' ? 'selected' : '' }}>Burger</option>
                                        <option value="Pizza" {{ $product->category == 'Pizza' ? 'selected' : '' }}>Pizza</option>
                                        <option value="Minuman" {{ $product->category == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                                        <option value="Snack" {{ $product->category == 'Snack' ? 'selected' : '' }}>Snack</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info updateProductBtn" data-id="{{ $product->product_id }}">Update</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                             </div>
                         </form>
                     </div>
                 </div>
                 @empty
                <tr>
                <td colspan="6" class="text-center">Belum ada data produk</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-3">{{ $products->links() }}</div>
</div>
    </div>
</div>

{{-- Modal Create Produk --}}
<div class="modal fade" id="createProductModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="productForm" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Gambar<span class="text-danger">*</span></label>
                        <input type="file" name="gambar" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Produk<span class="text-danger">*</span></label>
                        <input type="text" name="nama_product" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Satuan<span class="text-danger">*</span></label>
                        <input type="number" name="harga_satuan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stok<span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                     <div class="form-group">
                        <label>Kategori<span class="text-danger">*</span></label>
                        <select name="category" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Burger">Burger</option>
                            <option value="Pizza">Pizza</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Snack">Snack</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitProductBtn" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
//add product
document.getElementById('submitProductBtn').addEventListener('click', function () {
    var form = document.getElementById('productForm');

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
        text: 'Apakah Anda yakin ingin menyimpan produk ini?',
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
            text: 'Produk berhasil ditambahkan',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            form.submit(); // submit form setelah sweatalert
        });
    });
});

//search
$(document).ready(function(){
    $(".alert").delay(3000).slideUp(500);

    $("#searchInput").on("keyup", function(){
        var value = $(this).val().toLowerCase();
        $("#productTable tbody tr").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

// Edit
$(document).on('click', '.updateProductBtn', function(){
    let id = $(this).data('id');
    let form = document.getElementById('editProductForm' + id);

    if(!form.checkValidity()){ 
        form.reportValidity(); 
        return; 
    }
        $(form).closest('.modal').modal('hide');

    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin mengubah data produk ini?',
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

// Detail
$(document).on('click', '.detailProductBtn', function (e) {
    e.preventDefault();
    e.stopPropagation();

    let id = $(this).data('id');
    $('#detailProductModal' + id).modal('show');
});

// Hapus
$(document).on('click', '.deleteBtn', function (e) {
    e.preventDefault(); 
    let form = $(this).closest('form');

    Swal.fire({
        title: 'Hapus Data Produk?',
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

document.getElementById('categoryFilter').addEventListener('change', function() {
    let category = this.value;

    fetch(`/admin/product/filter/${category}`)
    .then(res => res.json())
    .then(data => {
        let tbody = document.querySelector('#productTable tbody');
        tbody.innerHTML = '';

        data.forEach((product, index) => {
            tbody.innerHTML += `<tr>
                <td class="text-center">${index + 1}</td>
                <td class="text-center">
                    ${product.gambar ? `<img src="/storage/${product.gambar}" width="50" class="img-thumbnail">` : '<span class="text-muted">No Image</span>'}
                </td>
                <td>${product.nama_product}</td>
                <td>Rp${Number(product.harga_satuan).toLocaleString('id-ID')}</td>
                <td class="text-center">${product.stok}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" style="color: white" data-target="#editProductModal${product.product_id}">Edit</button>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailProductModal${product.product_id}">Detail</button>
                    <form action="/admin/product/${product.product_id}" method="POST" class="d-inline deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm deleteBtn">Hapus</button>
                    </form>
                </td>
            </tr>`;
        });

        if(data.length === 0){
            tbody.innerHTML = `<tr><td colspan="6" class="text-center">Belum ada data produk</td></tr>`;
        }
    });
});
});

</script>
@endsection