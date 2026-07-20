<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\PurchaseInvoiceController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data
    Route::resource('products', ProductController::class)->middleware('can:products.view');
    Route::resource('vendors', VendorController::class)->middleware('can:vendors.view');
    Route::resource('customers', CustomerController::class)->middleware('can:customers.view');

    // Purchasing
    Route::resource('purchase-requests', PurchaseRequestController::class)->middleware('can:purchase_requests.view');
    Route::resource('purchase-orders', PurchaseOrderController::class)->middleware('can:purchase_orders.view');
    Route::resource('goods-receipts', GoodsReceiptController::class)->middleware('can:goods_receipts.view');
    Route::resource('purchase-invoices', PurchaseInvoiceController::class)->middleware('can:purchase_invoices.view');
    Route::resource('purchase-returns', PurchaseReturnController::class)->middleware('can:purchase_returns.view');

    // Sales
    Route::resource('sales-orders', SalesOrderController::class)->middleware('can:sales_orders.view');
    Route::resource('delivery-orders', DeliveryOrderController::class)->middleware('can:delivery_orders.view');
    Route::post('sales-invoices/{sales_invoice}/send-payment-link', [SalesInvoiceController::class, 'sendPaymentLink'])
        ->name('sales-invoices.send-payment-link')
        ->middleware('can:sales_invoices.edit');
    Route::resource('sales-invoices', SalesInvoiceController::class)->middleware('can:sales_invoices.view');
    Route::resource('sales-returns', SalesReturnController::class)->middleware('can:sales_returns.view');
    Route::resource('receipts', ReceiptController::class)->middleware('can:receipts.view');

    // Reports
    Route::get('reports/products', [ReportController::class, 'products'])->name('reports.products')->middleware('can:products.view');
    Route::get('reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases')->middleware('can:reports.purchases');
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales')->middleware('can:reports.sales');
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial')->middleware('can:reports.financial');

    // DataTables endpoints
    Route::get('reports/products/data', [ReportController::class, 'productsData'])->name('reports.products.data');
    Route::get('reports/purchases/data', [ReportController::class, 'purchasesData'])->name('reports.purchases.data');
    Route::get('reports/sales/data', [ReportController::class, 'salesData'])->name('reports.sales.data');
});

require __DIR__.'/auth.php';
