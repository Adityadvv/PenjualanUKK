<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Kasir')</title>
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
                <a href="{{ route('kasir.dashboard') }}" class="nav-link">Dashboard</a>
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

                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-light elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">Kasir Resto Nusantara</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column">
                    <li class="nav-item">
                        <a href="{{ route('kasir.dashboard') }}" class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard Menu</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kasir.listorder') }}" class="nav-link {{ request()->routeIs('kasir.listorder') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>List Order</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kasir.managemeja.index') }}" class="nav-link {{ request()->routeIs('kasir.managemeja.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Manage Meja</p>
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
@yield('js')
</body>
</html>