<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\InvinController;
use App\Http\Controllers\InvoutController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\DbConfigController;

Route::get('/config-db', [DbConfigController::class, 'index'])->name('db.config.index');
Route::post('/config-db', [DbConfigController::class, 'store'])->name('db.config.store');

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index')->middleware('permission:view-produk');
    Route::resource('produk', ProdukController::class)->except(['index'])->middleware('permission:manage-produk');
    
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index')->middleware('permission:view-pelanggan');
    Route::resource('pelanggan', PelangganController::class)->except(['index'])->middleware('permission:manage-pelanggan');

    Route::get('/pemasok', [PemasokController::class, 'index'])->name('pemasok.index')->middleware('permission:view-pemasok');
    Route::resource('pemasok', PemasokController::class)->except(['index'])->middleware('permission:manage-pemasok');

    // Transaksi Stok
    Route::get('/invin', [InvinController::class, 'index'])->name('invin.index')->middleware('permission:view-invin');
    Route::resource('invin', InvinController::class)->except(['index', 'show', 'edit', 'update'])->middleware('permission:manage-invin');

    Route::get('/invout', [InvoutController::class, 'index'])->name('invout.index')->middleware('permission:view-invout');
    Route::resource('invout', InvoutController::class)->except(['index', 'show', 'edit', 'update'])->middleware('permission:manage-invout');

    // POS (Point of Sale)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index')->middleware('permission:manage-pos');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store')->middleware('permission:manage-pos');
    Route::get('/pos/history', [PosController::class, 'history'])->name('pos.history')->middleware('permission:view-pos-history');
    Route::get('/pos/receipt/{id}', [PosController::class, 'showReceipt'])->name('pos.receipt')->middleware('permission:view-pos-history');

    // Pengeluaran (Expenses)
    Route::resource('pengeluaran', PengeluaranController::class)->middleware('permission:manage-settings');

    // Laporan & Log
    Route::get('/activity_log', [ActivityLogController::class, 'index'])->name('activity_log.index')->middleware('permission:view-activity-log');

    // Pengaturan
    Route::middleware('permission:manage-users')->group(function () {
        Route::resource('user', UserController::class);
    });

    Route::middleware('permission:manage-settings')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('permission:manage-permissions')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permission.index');
        Route::post('/permissions', [PermissionController::class, 'update'])->name('permission.update');
    });
});
