<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, PenggunaController, DashboardController, SupplierController, BahanBakuController, BahanBakuTransaksiController};

// Routes untuk login dan logout
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Routes untuk dashboard
Route::middleware(['auth.custom'])->group(function () {


    Route::get('/suppliers/bahan-baku/{bahanBakuId}', [SupplierController::class, 'getSuppliersByBahanBaku'])->name('suppliers.by-bahan-baku');
    Route::get('/laporan-bahan-baku', [BahanBakuController::class, 'generatePDF'])->name('laporan.bahan_baku');

    // Routes untuk Owner
    Route::middleware(['role:manajer_produksi'])->group(function () {
        // Dashboard
        Route::get('manajer-produksi/dashboard', [DashboardController::class, 'manajer_produksi'])->name('manajer_produksi.dashboard');

        // CRUD Supplier
        Route::get('manajer-produksi/supplier', [SupplierController::class, 'index'])->name('manajer_produksi.supplier.index');
        Route::get('manajer-produksi/supplier/create', [SupplierController::class, 'create'])->name('manajer_produksi.supplier.create');
        Route::post('manajer-produksi/supplier', [SupplierController::class, 'store'])->name('manajer_produksi.supplier.store');
        Route::get('manajer-produksi/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('manajer_produksi.supplier.edit');
        Route::put('manajer-produksi/supplier/{id}', [SupplierController::class, 'update'])->name('manajer_produksi.supplier.update');
        Route::delete('manajer-produksi/supplier/{id}', [SupplierController::class, 'destroy'])->name('manajer_produksi.supplier.destroy');

        // CRUD BahanBaku
        Route::get('manajer-produksi/bahan-baku', [BahanBakuController::class, 'index'])->name('manajer_produksi.bahan_baku.index');
        Route::get('manajer-produksi/bahan-baku/create', [BahanBakuController::class, 'create'])->name('manajer_produksi.bahan_baku.create');
        Route::post('manajer-produksi/bahan-baku', [BahanBakuController::class, 'store'])->name('manajer_produksi.bahan_baku.store');
        Route::get('manajer-produksi/bahan-baku/{id}/edit', [BahanBakuController::class, 'edit'])->name('manajer_produksi.bahan_baku.edit');
        Route::put('manajer-produksi/bahan-baku/{id}', [BahanBakuController::class, 'update'])->name('manajer_produksi.bahan_baku.update');
        Route::delete('manajer-produksi/bahan-baku/{id}', [BahanBakuController::class, 'destroy'])->name('manajer_produksi.bahan_baku.destroy');
        Route::get('manajer-produksi/bahan-baku/histori', [BahanBakuController::class, 'getDataMonthYear'])->name('manajer_produksi.bahan_baku.getDataMonthYear');

        // CRUD History BahanBaku
        Route::get('manajer-produksi/transaksi', [BahanBakuTransaksiController::class, 'index'])->name('manajer_produksi.transaksi.index');
        Route::get('manajer-produksi/transaksi/create', [BahanBakuTransaksiController::class, 'create'])->name('manajer_produksi.transaksi.create');
        Route::post('manajer-produksi/transaksi', [BahanBakuTransaksiController::class, 'store'])->name('manajer_produksi.transaksi.store');
        Route::get('manajer-produksi/transaksi/{id}/edit', [BahanBakuTransaksiController::class, 'edit'])->name('manajer_produksi.transaksi.edit');
        Route::put('manajer-produksi/transaksi/{id}', [BahanBakuTransaksiController::class, 'update'])->name('manajer_produksi.transaksi.update');
        Route::delete('manajer-produksi/transaksi/{id}', [BahanBakuTransaksiController::class, 'destroy'])->name('manajer_produksi.transaksi.destroy');
    });

    Route::middleware(['role:supervisor'])->group(function () {
        // Dashboard
        Route::get('supervisor/dashboard', [DashboardController::class, 'supervisor'])->name('supervisor.dashboard');

        // CRUD History BahanBaku
        Route::get('supervisor/transaksi', [BahanBakuTransaksiController::class, 'index'])->name('supervisor.transaksi.index');
        Route::get('supervisor/transaksi/create', [BahanBakuTransaksiController::class, 'create'])->name('supervisor.transaksi.create');
        Route::post('supervisor/transaksi', [BahanBakuTransaksiController::class, 'store'])->name('supervisor.transaksi.store');
        Route::get('supervisor/transaksi/{id}/edit', [BahanBakuTransaksiController::class, 'edit'])->name('supervisor.transaksi.edit');
        Route::put('supervisor/transaksi/{id}', [BahanBakuTransaksiController::class, 'update'])->name('supervisor.transaksi.update');
        Route::delete('supervisor/transaksi/{id}', [BahanBakuTransaksiController::class, 'destroy'])->name('supervisor.transaksi.destroy');
    });

    Route::middleware(['role:admin'])->group(function () {
        // Dashboard
        Route::get('admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

        // CRUD Pengguna
        Route::get('admin/pengguna', [PenggunaController::class, 'index'])->name('admin.pengguna.index');
        Route::get('admin/pengguna/create', [PenggunaController::class, 'create'])->name('admin.pengguna.create');
        Route::post('admin/pengguna', [PenggunaController::class, 'store'])->name('admin.pengguna.store');
        Route::get('admin/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('admin.pengguna.edit');
        Route::put('admin/pengguna/{id}', [PenggunaController::class, 'update'])->name('admin.pengguna.update');
        Route::delete('admin/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('admin.pengguna.destroy');

        // CRUD Supplier
        Route::get('admin/supplier', [SupplierController::class, 'index'])->name('admin.supplier.index');
        Route::get('admin/supplier/create', [SupplierController::class, 'create'])->name('admin.supplier.create');
        Route::post('admin/supplier', [SupplierController::class, 'store'])->name('admin.supplier.store');
        Route::get('admin/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('admin.supplier.edit');
        Route::put('admin/supplier/{id}', [SupplierController::class, 'update'])->name('admin.supplier.update');
        Route::delete('admin/supplier/{id}', [SupplierController::class, 'destroy'])->name('admin.supplier.destroy');

        // CRUD BahanBaku
        Route::get('admin/bahan-baku/master', [BahanBakuController::class, 'index'])->name('admin.bahan_baku.index');
        Route::get('admin/bahan-baku/master/create', [BahanBakuController::class, 'create'])->name('admin.bahan_baku.create');
        Route::post('admin/bahan-baku/master', [BahanBakuController::class, 'store'])->name('admin.bahan_baku.store');
        Route::get('admin/bahan-baku/master/{id}/edit', [BahanBakuController::class, 'edit'])->name('admin.bahan_baku.edit');
        Route::put('admin/bahan-baku/master/{id}', [BahanBakuController::class, 'update'])->name('admin.bahan_baku.update');
        Route::delete('admin/bahan-baku/master/{id}', [BahanBakuController::class, 'destroy'])->name('admin.bahan_baku.destroy');
        Route::get('admin/bahan-baku/master/histori', [BahanBakuController::class, 'getDataMonthYear'])->name('admin.bahan_baku.getDataMonthYear');

        // CRUD Transaksi BahanBaku
        Route::get('admin/bahan-baku/transaksi', [BahanBakuTransaksiController::class, 'index'])->name('admin.transaksi.index');
        Route::get('admin/bahan-baku/transaksi/create', [BahanBakuTransaksiController::class, 'create'])->name('admin.transaksi.create');
        Route::post('admin/bahan-baku/transaksi', [BahanBakuTransaksiController::class, 'store'])->name('admin.transaksi.store');
        Route::get('admin/bahan-baku/transaksi/{id}/edit', [BahanBakuTransaksiController::class, 'edit'])->name('admin.transaksi.edit');
        Route::put('admin/bahan-baku/transaksi/{id}', [BahanBakuTransaksiController::class, 'update'])->name('admin.transaksi.update');
        Route::delete('admin/bahan-baku/transaksi/{id}', [BahanBakuTransaksiController::class, 'destroy'])->name('admin.transaksi.destroy');
    });
});
