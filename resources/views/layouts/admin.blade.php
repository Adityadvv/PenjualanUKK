<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin')</title>
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" style="min-height: 100vh;">

     <!-- Navbar -->
        <ul class="navbar-nav ml-auto">
        <!-- Menu tambahan di atas -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
                <li class="nav-item d-none d-sm-inline-block">
                <a href="" class="nav-link">Resto Management System</a>
            </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
            <!-- User Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                </a>
            </li>
            </ul>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
            <button class="logout-btn" type="submit"> Logout</button>
            </form>
        </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-light elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">Resto Nusantara</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.detailtransaksi') }}" class="nav-link {{ request()->routeIs('admin.detailtransaksi') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Detail Transaksi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.datapelanggan') }}" class="nav-link {{ request()->routeIs('admin.datapelanggan') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Data Pelanggan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('product.index') }}" class="nav-link {{ request()->routeIs('product.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Manage Produk</p>
                        </a>
                    </li>
                <li class="nav-item has-treeview {{ request()->is('admin/inventorybarang/*') || request()->is('admin/transaksibarang') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/inventorybarang/*') || request()->is('admin/transaksibarang') ? 'active' : '' }}" id="inventoryMenu">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Inventory Barang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->is('admin/inventorybarang/inventory') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Barang & Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inventory.transaksi') }}" class="nav-link {{ request()->is('admin/transaksibarang') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transaksi Barang</p>
                            </a>
                        </li>
                    </ul>
                </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.setting') }}" class="nav-link {{ request()->routeIs('admin.setting') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>User Management</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Konten halaman -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">Admin Kasir</div>
        <strong>&copy; 2025 Kasir App.</strong> All rights reserved.
    </footer>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ambil semua treeview menu
    const treeviews = document.querySelectorAll('.nav-item.has-treeview');

    treeviews.forEach(treeview => {
        const link = treeview.querySelector('a.nav-link');

        // Klik parent toggle submenu
        link.addEventListener('click', function(e){
            e.preventDefault();
            // Toggle menu-open hanya kalau klik parent, jangan reset submenu aktif
            treeview.classList.toggle('menu-open');
            link.classList.toggle('active');
        });

        // Cek halaman aktif dari URL untuk submenu
        const subLinks = treeview.querySelectorAll('.nav-treeview .nav-link');
        subLinks.forEach(subLink => {
            if(subLink.href === window.location.href){
                subLink.classList.add('active');
                treeview.classList.add('menu-open'); // buka parent jika submenu aktif
                link.classList.add('active'); // highlight parent
            }
        });
    });
});
</script>

<style>
    /* Submenu aktif */
.nav-treeview .nav-link.active {
    background-color: #b3d7ff;
    color: #000;
}

/* Parent menu aktif tetap default */
.nav-item.has-treeview > .nav-link.active {
    background-color: #007bff;
    color: #fff;
}

/* Hover submenu */
.nav-treeview .nav-link:hover {
    background-color: #cce6ff;
}

.logout-btn{
        background-color: #ef4646ff;
        color: white;
        border-radius: 6px;
}
</style>

@yield('js')
</body>
</html>
