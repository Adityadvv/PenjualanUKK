<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DetailTransaksiController;
use App\Http\Controllers\Admin\TransaksiBarangController;
use App\Http\Controllers\Admin\DataPelangganController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Kasir\ListOrderController;
use App\Http\Controllers\Kasir\MejaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $role = Auth::user()->role;
    return $role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('kasir.dashboard');
});


Route::middleware(['auth'])->group(function () {

        // Dashboard
        Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
        // ->middleware('role:admin');
        Route::get('kasir/dashboard', [KasirDashboardController::class, 'index'])
        ->name('kasir.dashboard');
        Route::post('kasir/dashboard', [KasirDashboardController::class, 'store'])
        ->name('dashboard.store');
        // ->middleware('role:karyawan');

        //ListOrder
        Route::get('kasir/listorder', [ListOrderController::class, 'index'])
        ->name('kasir.listorder');
        // Proses pembayaran & kembalikan struk HTML
        Route::post('kasir/listorder/{id}/bayar', [ListOrderController::class, 'bayar'])
        ->name('kasir.listorder.bayar');
        // ->middleware('role:karyawan');

        //Manage Meja
        Route::get('kasir/daftarmeja', [MejaController::class, 'index'])->name('kasir.daftarmeja.index');
        Route::post('kasir/daftarmeja', [MejaController::class, 'store'])->name('kasir.daftarmeja.store');

        //Transaksi
        Route::get('admin/detailtransaksi', [DetailTransaksiController::class, 'index'])
        ->name('admin.detailtransaksi');
        // ->middleware('role:admin');

        //Data Pelanggan
        Route::get('admin/pelanggan', [DataPelangganController::class, 'index'])
        ->name('admin.datapelanggan');
        // ->middleware('role:admin');

        //Product
         Route::get('admin/product', [ProductController::class, 'index'])
        ->name('product.index');
         Route::post('admin/product', [ProductController::class, 'store'])
        ->name('product.store');
         Route::put('admin/product/{id}', [ProductController::class, 'update'])
        ->name('product.update');
         Route::delete('admin/product/{id}', [ProductController::class, 'destroy'])
        ->name('product.destroy');
        Route::get('admin/product/filter/{category?}', [ProductController::class, 'filter'])
        ->name('product.filter');
        // ->middleware('role:admin');

        //Inventory ( Barang dan Supplier )
        Route::get('admin/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('admin/inventory', [InventoryController::class, 'store'])->name('inventory.store');
        Route::put('admin/inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('admin/inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

        // Transaksi Barang
        Route::get('admin/transaksibarang', [TransaksiBarangController::class,'index'])->name('inventory.transaksi');
        Route::post('admin/transaksibarang', [TransaksiBarangController::class,'store'])->name('inventory.transaksi.store');

        //Setting ( User Management )
         Route::get('admin/setting', [UserController::class, 'index'])
        ->name('admin.setting');
         Route::post('admin/setting/users', [UserController::class, 'store'])
        ->name('users.store');
         Route::put('admin/setting/users/{user}', [UserController::class, 'update'])
        ->name('users.update');
         Route::delete('admin/setting/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');
        // ->middleware('role:admin');

        });

        Route::get('storage/{path}', function ($path) {
    $fullPath = storage_path("app/public/{$path}");

    if (!file_exists($fullPath)) {
        abort(404);
    }

    // Ambil ekstensi untuk atur Content-Type
    $mimeType = mime_content_type($fullPath);
    return response()->file($fullPath, ['Content-Type' => $mimeType]);
})->where('path', '.*');
        

require __DIR__.'/auth.php';

        // Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

//     // Dashboard
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     // Transaksi
//     Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    
//     // Product
//     Route::get('/product', [ProductController::class, 'index'])->name('product.index');
//     Route::post('/product', [ProductController::class, 'store'])->name('product.store');
//     Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
//     Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    
//     // Inventory (Produk & Supplier)
//     Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
//     Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
//     Route::put('/inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
//     Route::delete('/inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

//     // Setting & Users
//     Route::get('/setting', [UserController::class, 'index'])->name('setting');
//     Route::post('/setting/users', [UserController::class, 'store'])->name('users.store');
//     Route::put('/setting/users/{user}', [UserController::class, 'update'])->name('users.update');
//     Route::delete('/setting/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
// });

// // Profile
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });