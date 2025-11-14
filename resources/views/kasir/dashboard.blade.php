@extends('layouts.kasir')

@section('title', 'Dashboard Kasir')

@section('content')
<section class="content">
    <div class="container-fluid pt-3">
        <div class="row">

            {{-- Bagian Kiri: Kategori dan Produk --}}
            <div class="col-md-8 d-flex flex-column" style="height: 80vh;">
                {{-- Card Kategori --}}
                <div class="card mb-3 p-3 shadow-sm">
                    <h5 class="mb-3">Menu Category</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-secondary btn-sm category-btn" data-category="">Semua Produk</button>
                        @foreach ($categories as $category)
                            <button class="btn btn-outline-primary btn-sm category-btn" data-category="{{ $category }}">{{ $category }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Card Produk --}}
                <div class="card flex-grow-1 shadow-sm p-3" style="overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                    <div class="row" id="menuProducts">
                        @foreach ($products as $product)
                            <div class="col-md-4 mb-3 product-item" data-category="{{ $product->category }}">
                                <div class="card text-center p-2 hover-shadow">
                                    <img src="{{ $product->gambar ? asset('storage/'.$product->gambar) : asset('images/no-image.png') }}" 
                                        class="img-fluid mb-2" alt="{{ $product->nama_product }}">
                                    <h6 class="mb-1">{{ $product->nama_product }}</h6>
                                    <p class="text-muted mb-1">Rp {{ number_format($product->harga_satuan,0,',','.') }}</p>
                                    <p class="text-muted mb-1">Stok: {{ $product->stok }}</p>
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
            </div>

            {{-- Bagian Kanan: Customer + Order Menu --}}
            <div class="col-md-4 d-flex flex-column" style="height: 80vh;">
                <div class="card shadow-sm p-3 d-flex flex-column" style="border-radius: 15px; background-color: #ffffff; min-height: 90vh;">
                    
                    {{-- Bagian Customer --}}
                    <div class="flex-grow-0 mb-3">
                        <h5 class="mb-3 text-primary fw-bold">🧍‍♂️ Data Customer</h5>
                        <form id="customerForm" action="{{ route('dashboard.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="fw-semibold">Nama Pelanggan</label>
                                <input type="text" name="namapelanggan" class="form-control" placeholder="Masukkan nama pelanggan" style="border-radius: 10px;" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="fw-semibold">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2" placeholder="Masukkan alamat" style="border-radius: 10px;"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="fw-semibold">No Telepon</label>
                                <input type="text" name="no_telepon" class="form-control" placeholder="Masukkan nomor telepon" style="border-radius: 10px;">
                            </div>
                        </form>
                    </div>

                    <hr>

                    {{-- Bagian Order Menu (scrollable) --}}
                    <div class="flex-grow-1 mb-3" style="overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                        <h5 class="mb-3 text-primary fw-bold">🧾 Order Menu</h5>
                        <ul class="list-group mb-0" id="orderList">
                            {{-- Daftar order akan muncul di sini --}}
                        </ul>
                    </div>

                    {{-- Bagian Total + Tombol Order --}}
                    <div class="flex-grow-0">
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="totalPrice" data-total="0">Rp 0</strong>
                        </div>
                        <button type="button" id="submitOrderBtn" class="btn w-100 text-white fw-bold py-2" style="background-color: #ff4d4f; border-radius: 10px;">
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

// Order logic
let orderList = document.querySelector('#orderList');
let totalEl = document.querySelector('#totalPrice');
let orders = {}; // id produk => {name, price, qty, total}

document.querySelectorAll('.add-to-order').forEach(btn => {
    btn.addEventListener('click', function(){
        let id = this.dataset.id;
        let name = this.dataset.name;
        let price = parseInt(this.dataset.price);

        if(orders[id]){
            orders[id].qty += 1;
            orders[id].total = orders[id].qty * price;
            let li = document.querySelector(`#order-item-${id}`);
            li.querySelector('.item-qty').textContent = orders[id].qty;
            li.querySelector('.item-total').textContent = 'Rp ' + orders[id].total.toLocaleString('id-ID');
        } else {
            orders[id] = { name: name, price: price, qty: 1, total: price };
            let li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center border-0 border-bottom';
            li.id = `order-item-${id}`;
            li.innerHTML = `
                <span>${name} (<span class="item-qty">1</span>)</span>
                <div>
                    <span class="item-total">Rp ${price.toLocaleString('id-ID')}</span>
                    <button type="button" class="btn btn-sm btn-danger ms-2 btn-cancel-item">×</button>
                </div>
            `;
            orderList.appendChild(li);

            li.querySelector('.btn-cancel-item').addEventListener('click', function(){
                if(orders[id].qty > 1){
                    orders[id].qty -= 1;
                    orders[id].total = orders[id].qty * price;
                    li.querySelector('.item-qty').textContent = orders[id].qty;
                    li.querySelector('.item-total').textContent = 'Rp ' + orders[id].total.toLocaleString('id-ID');
                } else {
                    delete orders[id];
                    li.remove();
                }
                updateTotal();
            });
        }

        updateTotal();
    });
});

function updateTotal(){
    let total = 0;
    for(let key in orders){
        total += orders[key].total;
    }
    totalEl.dataset.total = total;
    totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Submit order
document.getElementById('submitOrderBtn').addEventListener('click', function(){
    let customerForm = document.getElementById('customerForm');
    if(customerForm.namapelanggan.value.trim() === ''){
        alert('Nama pelanggan harus diisi!');
        return;
    }
    customerForm.submit();
});
</script>

<style>
/* Hilangkan garis scroll pada card produk dan order menu */
.card.flex-grow-1::-webkit-scrollbar {
    display: none;
}
.card.flex-grow-1 {
    -ms-overflow-style: none;  /* IE & Edge */
    scrollbar-width: none;     /* Firefox */
}
</style>
@endsection