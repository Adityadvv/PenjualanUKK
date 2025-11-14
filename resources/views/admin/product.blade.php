@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="container-fluid pt-3">
    <h3>Manajemen Produk</h3>
    <hr>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card shadow-sm rounded">
        <div class="card-body">
            {{-- Tombol tambah + search --}}
            <div class="d-flex mb-3 justify-content-between">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createProductModal">
                    + Tambah Produk
                </button>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari nama produk..." style="max-width: 300px;">
            </div>

            <div class="mb-3" style="max-width: 300px;">
                <label for="categoryFilter">Filter Kategori:</label>
                <select id="categoryFilter" class="form-control">
                    <option value="">Semua Kategori</option>
                    <option value="Burger">Burger</option>
                    <option value="Pizza">Pizza</option>
                    <option value="Minuman">Minuman</option>
                    <option value="Snack">Snack</option>
                </select>
            </div>

            {{-- Tabel produk --}}
            <table class="table table-bordered table-striped table-hover" id="productTable">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga Satuan</th>
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
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editProductModal{{ $product->product_id }}">Edit</button>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailProductModal{{ $product->product_id }}">Detail</button>
                                <form action="{{ route('product.destroy', $product->product_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
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
                                <form action="{{ route('product.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Edit Produk</h5>
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
                                            <button class="btn btn-primary">Simpan</button>
                                            <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" name="gambar" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_product" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="number" name="harga_satuan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                     <div class="form-group">
                        <label>Kategori</label>
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
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>



@endsection

@section('js')
<script>
$(document).ready(function(){
    $(".alert").delay(3000).slideUp(500);

    $("#searchInput").on("keyup", function(){
        var value = $(this).val().toLowerCase();
        $("#productTable tbody tr").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Edit
$(document).on('click', '.btn-warning', function(){
    let id = $(this).data('id');
    $('#editProductModal' + id).modal('show');
});

// Detail
$(document).on('click', '.btn-info', function(){
    let id = $(this).data('id');
    $('#detailProductModal' + id).modal('show');
});

// Hapus
$(document).on('click', '.btn-danger', function(){
    if(confirm('Yakin ingin menghapus produk ini?')){
        let id = $(this).data('id');
        $(`#deleteForm${id}`).submit(); // pastikan form punya id
    }
});

    document.getElementById('categoryFilter').addEventListener('change', function() {
    let category = this.value;

    fetch(`/admin/product/filter/${category}`)
    .then(res => res.json())
    .then(data => {
        let tbody = document.querySelector('#productTable tbody');
        tbody.innerHTML = ''; // reset tabel sebelum diisi

        // <-- BAGIAN INI -->
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
                    <button class="btn btn-warning btn-sm" data-id="${product.product_id}" data-toggle="modal" data-target="#editProductModal${product.product_id}">Edit</button>
                    <button class="btn btn-info btn-sm" data-id="${product.product_id}" data-toggle="modal" data-target="#detailProductModal${product.product_id}">Detail</button>
                    <form action="/admin/product/${product.product_id}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus produk ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>`;
        });

        if(data.length === 0){
            tbody.innerHTML = `<tr><td colspan="6" class="text-center">Belum ada data produk</td></tr>`;
        }
    });
});

    
// $('#createProductModal form').submit(function(e){
//     e.preventDefault();
//     let formData = new FormData(this);
//     $.ajax({
//         url: $(this).attr('action'),
//         type: 'POST',
//         data: formData,
//         contentType: false,
//         processData: false,
//         success: function(res){
//             alert(res.message);
//             location.reload();
//         },
//         error: function(xhr){
//             console.log(xhr.responseJSON); // <-- Lihat error validasi
//             if(xhr.status === 422){
//                 let errors = xhr.responseJSON.errors;
//                 let msg = '';
//                 for(let key in errors){
//                     msg += errors[key].join(', ') + '\n';
//                 }
//                 alert(msg);
//             } else {
//                 alert('Terjadi kesalahan server');
//             }
//         }
//     });
// });


});

</script>
@endsection