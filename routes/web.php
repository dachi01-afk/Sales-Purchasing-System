<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DOController;
use App\Http\Controllers\InvoicePurchasingController;
use App\Http\Controllers\InvoiceSalesController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\POController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturPurchasingController;
use App\Http\Controllers\ReturSalesController;
use App\Http\Controllers\SOController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data
    Route::resource('barang', BarangController::class)->middleware('can:barang.view');
    Route::resource('vendor', VendorController::class)->middleware('can:vendor.view');
    Route::resource('customer', CustomerController::class)->middleware('can:customer.view');

    // Purchasing
    Route::resource('permintaan', PermintaanController::class)->middleware('can:permintaan.view');
    Route::resource('po', POController::class)->middleware('can:po.view');
    Route::resource('penerimaan', PenerimaanController::class)->middleware('can:penerimaan.view');
    Route::resource('invoice-purchasing', InvoicePurchasingController::class)->middleware('can:invoice_purchasing.view');
    Route::resource('retur-purchasing', ReturPurchasingController::class)->middleware('can:retur_purchasing.view');

    // Sales
    Route::resource('so', SOController::class)->middleware('can:so.view');
    Route::resource('do', DOController::class)->middleware('can:do.view');
    Route::resource('invoice-sales', InvoiceSalesController::class)->middleware('can:invoice_sales.view');
    Route::resource('retur-sales', ReturSalesController::class)->middleware('can:retur_sales.view');
    Route::resource('kwitansi', KwitansiController::class)->middleware('can:kwitansi.view');

    // Reports
    Route::get('laporan/pembelian', [LaporanController::class, 'pembelian'])->name('laporan.pembelian')->middleware('can:laporan.pembelian');
    Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan')->middleware('can:laporan.penjualan');
    Route::get('laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan')->middleware('can:laporan.keuangan');
});

require __DIR__.'/auth.php';
