@extends('layouts.kasir')

@section('title', 'Dashboard Kasir')

@section('content')
<section class="content">
    <div class="container-fluid pt-3">
        <div class="row">

            {{--Kategori dan Produk --}}
            <div class="col-md-8 d-flex flex-column" style="height: 80vh;">
                {{-- Card Kategori --}}
                <div class="card mb-3 p-3 shadow-sm">
                    <h5 class="mb-3">Menu Category</h5>
                        <div class="d-flex flex-wrap">
                            <button class="btn btn-outline-primary btn-sm px-2 py-1 mr-2 mb-2 category-btn" data-category="">Semua Menu</button>
                            @foreach ($categories as $category)
                            <button class="btn btn-outline-primary btn-sm px-2 py-1 mr-2 mb-2 category-btn"
                            data-category="{{ $category }}">
                        {{ $category }}
                    </button>
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
                                        class="product-img img-fluid mb-2" alt="{{ $product->nama_product }}">
                                    <h6 class="mb-1">{{ $product->nama_product }}</h6>
                                    <p class="text-muted mb-1">Rp {{ number_format($product->harga_satuan,0,',','.') }}</p>
                                    <p class="text-muted mb-1">Stok: {{ $product->stok }}</p>
                                    <button class="btn btn-sm btn-primary add-to-order"
                                            data-id="{{ $product->product_id }}"
                                            data-name="{{ $product->nama_product }}"
                                            data-price="{{ $product->harga_satuan }}">
                                        Add +
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan: Customer + Order Menu --}}
            <div class="col-md-4 d-flex flex-column" style="height: 80vh;">
                <div class="card shadow-sm p-3 d-flex flex-column" 
                    style="border-radius: 15px; background-color: #ffffff; width: 450px; min-height: 90vh;">

                    {{-- Bagian Customer --}}
                    <div class="flex-shrink-0 mb-3">
                        <h5 class="mb-3 text-primary fw-bold">🧍‍♂️ Data Customer</h5>
                        <form id="customerForm" action="{{ route('dashboard.store') }}" method="POST">
                            @csrf
                            {{-- Data Customer --}}
                            <div class="row g-2 mb-3 align-items-center">
                                <div class="col-md-4"><label class="fw-semibold">Nama Customer</label></div>
                                <div class="col-md-8"><input type="text" name="namapelanggan" class="form-control" placeholder="Masukkan nama pelanggan" style="height: 38px;" required></div>
                            </div>
                            <div class="row g-2 mb-3 align-items-center">
                                <div class="col-md-4"><label class="fw-semibold">Alamat</label></div>
                                <div class="col-md-8"><input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat" style="height: 38px;" required></div>
                            </div>
                            <div class="row g-2 mb-3 align-items-center">
                                <div class="col-md-4"><label class="fw-semibold">No Telepon</label></div>
                                <div class="col-md-8"><input type="number" name="no_telepon" class="form-control" placeholder="Masukkan nomor telepon" style="height: 38px;" required></div>
                            </div>

                            {{-- Tipe Pesanan --}}
                            <div class="row g-2 mb-3 align-items-center">
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Tipe Pesanan</label>
                                </div>
                                <div class="col-md-8 d-flex">
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" type="radio" name="tipe_pesanan" id="dineIn" value="dine_in" required>
                                        <label class="form-check-label" for="dineIn">Dine In</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe_pesanan" id="takeAway" value="take_away" required>
                                        <label class="form-check-label" for="takeAway">Take Away</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Nomor Meja --}}
                            <div class="row g-2 mb-3 align-items-center" id="nomorMejaWrapper">
                                <div class="col-md-4"><label class="fw-semibold">Nomor Meja</label></div>
                                <div class="col-md-8">
                                    <select name="nomor_meja" id="nomorMejaInput" class="form-select" style="height: 38px; width: 100%;">
                                        @foreach ($mejas as $meja)
                                            <option value="{{ $meja->nomor_meja }}">
                                                Meja {{ str_pad($meja->nomor_meja, 2, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- Metode Pembayaran --}}
                            <div class="row g-2 mb-3 align-items-center">
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Metode Pembayaran</label>
                                </div>
                                <div class="col-md-8 d-flex">
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="cash" value="cash" required>
                                        <label class="form-check-label" for="cash">Cash</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="qris" value="qris" required>
                                        <label class="form-check-label" for="qris">QRIS</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Input hidden order_data akan dibuat otomatis lewat JS --}}
                        </form>
                    </div>

                    <hr class="my-2">

                    {{-- Bagian Order Menu --}}
                    <div class="d-flex flex-column flex-grow-1" style="min-height:0;">
                        {{-- Judul Order Menu (tetap) --}}
                        <h5 class="mb-3 text-primary fw-bold d-flex justify-content-between align-items-center flex-shrink-0">
                            🧾 Order Menu
                            <span id="menuCount" class="badge bg-primary" style="font-size: 13px;">0 Menu</span>
                        </h5>

                        {{-- List order scrollable --}}
                        <ul id="orderList" class="list-group flex-grow-1 mb-3" 
                            style="overflow-y:auto; min-height:0; max-height: 250px; scrollbar-width: none; -ms-overflow-style: none;">
                            {{-- Daftar order muncul di sini --}}
                        </ul>

                        {{-- Total + Tombol Order --}}
                        <div class="flex-shrink-0">
                            <div class="mb-2 d-flex justify-content-between">
                                <strong>Total Item:</strong>
                                <span id="totalItemCount">0</span>
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <strong>Total Harga:</strong>
                                <strong id="totalPrice" data-total="0">Rp 0</strong>
                            </div>

                            <button type="button" id="submitOrderBtn" class="btn w-100 text-white fw-bold py-2"
                                style="background-color: #ff4d4f; border-radius: 10px;">
                                🛒 Order Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

//Filter Kategori produk
document.querySelectorAll('.category-btn').forEach(
    btn => { btn.addEventListener('click', function(){
        let category = this.dataset.category; 

document.querySelectorAll('.product-item').forEach(item => {
    if (category === '' || item.dataset.category === category){
        item.style.display = 'block'; } 
        else 
        { item.style.display = 'none'; } }); }); });
    
// Array menyimpan order: {id, name, price, qty}
let orderItems = [];

// helper: cari index item berdasar id
function findIndexById(id){
    return orderItems.findIndex(it => String(it.id) === String(id));
}

// Tambah item lewat tombol Add 
document.querySelectorAll('.add-to-order').forEach(btn => {
    btn.addEventListener('click', function(){
        let id = this.dataset.id;
        let name = this.dataset.name;
        let price = parseInt(this.dataset.price);

        let idx = findIndexById(id);
        if(idx !== -1){
            orderItems[idx].qty += 1;
        } else {
            orderItems.push({id, name, price, qty: 1});
        }

        renderOrderList();
        updateTotal();
        updateTotalItemCount();
        updateMenuCount();
    });
});

// Ambil elemen radio tipe pesanan dan wrapper nomor meja
const dineInRadio = document.getElementById('dineIn');
const takeAwayRadio = document.getElementById('takeAway');
const nomorMejaWrapper = document.getElementById('nomorMejaWrapper');

// Fungsi toggle nomor meja
function toggleNomorMeja() {
    if(dineInRadio.checked){
        nomorMejaWrapper.style.display = 'flex';
    } else {
        nomorMejaWrapper.style.display = 'none';
    }
}

// Inisialisasi saat halaman load
toggleNomorMeja();

// Tambahkan event listener ke radio
dineInRadio.addEventListener('change', toggleNomorMeja);
takeAwayRadio.addEventListener('change', toggleNomorMeja);


// Render list order
function renderOrderList(){
    let list = document.getElementById('orderList');
    list.innerHTML = '';

    orderItems.forEach((item, index) => {
        let itemTotal = item.price * item.qty;
        let li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center border-0 border-bottom';
        li.dataset.index = index;
        li.innerHTML = `
            <div>
                <strong>${escapeHtml(item.name)}</strong><br>
                <small>Rp ${item.price.toLocaleString('id-ID')} x ${item.qty} = Rp ${itemTotal.toLocaleString('id-ID')}</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-danger btn-reduce" data-index="${index}">-</button>
                <span class="mx-1">${item.qty}</span>
                <button class="btn btn-sm btn-outline-success btn-increase" data-index="${index}">+</button>
                <button class="btn btn-sm btn-danger btn-delete ms-2" data-index="${index}">×</button>
            </div>
        `;
        list.appendChild(li);
    });

    attachOrderButtons();
}

// Escape sederhana untuk nama produk
function escapeHtml(text) {
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Attach event tombol + / - / delete
function attachOrderButtons(){
    document.querySelectorAll('.btn-increase').forEach(btn => {
        btn.removeEventListener('click', onIncrease);
        btn.addEventListener('click', onIncrease);
    });
    document.querySelectorAll('.btn-reduce').forEach(btn => {
        btn.removeEventListener('click', onReduce);
        btn.addEventListener('click', onReduce);
    });
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.removeEventListener('click', onDelete);
        btn.addEventListener('click', onDelete);
    });
}

function onIncrease(){
    let idx = parseInt(this.dataset.index);
    if(!isNaN(idx) && orderItems[idx]){
        orderItems[idx].qty++;
        renderOrderList();
        updateTotal();
        updateTotalItemCount();
        updateMenuCount();
    }
}

function onReduce(){
    let idx = parseInt(this.dataset.index);
    if(!isNaN(idx) && orderItems[idx]){
        orderItems[idx].qty--;
        if(orderItems[idx].qty <= 0){
            orderItems.splice(idx, 1);
        }
        renderOrderList();
        updateTotal();
        updateTotalItemCount();
        updateMenuCount();
    }
}

function onDelete(){
    let idx = parseInt(this.dataset.index);
    if(!isNaN(idx) && orderItems[idx]){
        orderItems.splice(idx, 1);
        renderOrderList();
        updateTotal();
        updateTotalItemCount();
        updateMenuCount();
    }
}

// Hitung Total Harga
function updateTotal(){
    let total = orderItems.reduce((sum, it) => sum + (it.price * it.qty), 0);
    let totalEl = document.getElementById('totalPrice');
    if(totalEl){
        totalEl.dataset.total = total;
        totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
}

// Hitung Total Item (semua qty)
function updateTotalItemCount(){
    let totalItem = orderItems.reduce((sum, it) => sum + it.qty, 0);
    let el = document.getElementById('totalItemCount');
    if(el){
        el.textContent = totalItem + " Item";
    }
}

// Hitung Total Menu (unik)
function updateMenuCount(){
    let menuCount = orderItems.length;
    let menuCountEl = document.getElementById('menuCount');
    if(menuCountEl){
        menuCountEl.textContent = menuCount + " Menu";
    }
}

// Submit order
let submitBtn = document.getElementById('submitOrderBtn');
if(submitBtn){
    submitBtn.addEventListener('click', function(){
        let customerForm = document.getElementById('customerForm');
        if(!customerForm){
            alert('Form customer tidak ditemukan');
            return;
        }

        let nama = customerForm.namapelanggan.value.trim();
        let alamat = customerForm.alamat.value.trim();
        let telepon = customerForm.no_telepon.value.trim();

    if(nama === '' || alamat === '' || telepon === ''){
    Swal.fire({
        icon: 'warning',
        title: 'Perhatian',
        text: 'Semua data pelanggan harus diisi!'
    });
    return;
    }

    if(orderItems.length === 0){
    Swal.fire({
        icon: 'warning',
        title: 'Perhatian',
        text: 'Belum ada menu yang dipesan!'
    });
    return;
    }

    // Cek metode pembayaran
    if(!customerForm.metode_pembayaran.value){
    Swal.fire({
        icon: 'warning',
        title: 'Perhatian',
        text: 'Pilih metode pembayaran!'
    });
    return;
    }

        // Hapus input hidden lama
        let existingHidden = document.getElementById('orderDataInput');
        if(existingHidden) existingHidden.remove();

        // Buat input hidden baru
        let hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'order_data';
        hidden.id = 'orderDataInput';
        hidden.value = JSON.stringify(orderItems);
        customerForm.appendChild(hidden);

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Pesanan berhasil diorder',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            customerForm.submit(); 
        });
    });
}

// Inisialisasi
renderOrderList();
updateTotal();
updateTotalItemCount();
updateMenuCount();

</script>

<style>
/* Hilangkan garis scroll bar di menu order */
.card.flex-grow-1::-webkit-scrollbar,
.flex-grow-1::-webkit-scrollbar {
    display: none;
}
.card.flex-grow-1,
.flex-grow-1 {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Tinggi Gambar produk Sama */
.product-item .product-img {
    width: 100%;        
    height: 150px;     
    object-fit: cover; 
    border-radius: 8px; 
}

/* card tetap tinggi */
.product-item .card {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
</style>
@endsection