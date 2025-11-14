@extends('layouts.kasir')

@section('title', 'Dashboard Kasir')

@section('content')
<section class="content">
    <div class="container-fluid pt-3">

        <div class="row">
            {{-- Bagian Kiri: Kategori dan Menu --}}
            <div class="col-md-8">
                {{-- Menu Category --}}
                <div class="card mb-3 p-3 shadow-sm">
                    <h5 class="mb-3">Menu Category</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($categories as $category)
                            <button class="btn btn-outline-primary btn-sm category-btn" data-category="{{ $category }}">{{ $category }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Pilih Order --}}
                <div class="row" id="menuProducts">
                    @foreach ($products as $product)
                        <div class="col-md-4 mb-3 product-item" data-category="{{ $product->category }}">
                            <div class="card text-center p-2 hover-shadow">
                                <img src="{{ $product->gambar ? asset('storage/'.$product->gambar) : asset('images/no-image.png') }}" class="img-fluid mb-2" alt="{{ $product->nama_product }}">
                                <h6 class="mb-1">{{ $product->nama_product }}</h6>
                                <p class="text-muted mb-1">Rp {{ number_format($product->harga_satuan,0,',','.') }}</p>
                                <button class="btn btn-sm btn-primary add-to-order"
                                        data-id="{{ $product->product_id }}"
                                        data-name="{{ $product->nama_product }}"
                                        data-price="{{ $product->harga_satuan }}">
                                    Add
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bagian Kanan: Input Pelanggan + Daftar Pesanan --}}
            <div class="col-md-4 d-flex flex-column" style="height: 80vh;">
                <div class="card p-3 shadow-sm d-flex flex-column flex-grow-1 justify-content-between"
                     style="border: none; border-radius: 15px; background-color: #ffffff; min-height: 90vh;">
                    
                    {{-- Bagian atas: Data Customer + Order --}}
                    <div class="flex-grow-1 overflow-auto">
                        <h5 class="mb-3 text-primary fw-bold">🧍‍♂️ Data Customer</h5>
                        <form>
                            <div class="form-group mb-3">
                                <label class="fw-semibold">Nama Pelanggan</label>
                                <input type="text" class="form-control" placeholder="Masukkan nama pelanggan" style="border-radius: 10px;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="fw-semibold">Alamat</label>
                                <textarea class="form-control" rows="2" placeholder="Masukkan alamat" style="border-radius: 10px;"></textarea>
                            </div>

                            <div class="form-group mb-4">
                                <label class="fw-semibold">No Telepon</label>
                                <input type="text" class="form-control" placeholder="Masukkan nomor telepon" style="border-radius: 10px;">
                            </div>
                        </form>

                        <hr>

                        <h5 class="mb-3 text-primary fw-bold">🧾 Order Menu</h5>
                        <ul class="list-group mb-3" id="orderList">
                            {{-- Daftar order akan muncul di sini --}}
                        </ul>

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="totalPrice" data-total="0">Rp 0</strong>
                        </div>
                    </div>

                    {{-- Bagian bawah: Tombol Order --}}
                    <div>
                        <button class="btn w-100 text-white fw-bold py-2"
                                style="background-color: #ff4d4f; border-radius: 10px;">
                            🛒 Order Sekarang
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', function(){
        let category = this.dataset.category;
        document.querySelectorAll('.product-item').forEach(item => {
            if(category === '' || item.dataset.category === category){
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

document.querySelectorAll('.add-to-order').forEach(btn => {
    btn.addEventListener('click', function(){
        let id = this.dataset.id;
        let name = this.dataset.name;
        let price = parseInt(this.dataset.price);

        let orderList = document.querySelector('.list-group');
        orderList.innerHTML += `<li class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom">
            ${name} <span>Rp ${price.toLocaleString('id-ID')}</span>
        </li>`;

        // Update total (opsional)
        let totalEl = document.querySelector('#totalPrice');
        let currentTotal = parseInt(totalEl.dataset.total || 0);
        currentTotal += price;
        totalEl.dataset.total = currentTotal;
        totalEl.textContent = 'Rp ' + currentTotal.toLocaleString('id-ID');
    });
});
</script>
@endsection