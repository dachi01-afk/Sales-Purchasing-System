<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\DeliveryOrder;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use App\Models\Receipt;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = now();
        $thisMonth = [$now->startOfMonth()->format('Y-m-d'), $now->copy()->endOfMonth()->format('Y-m-d')];

        $totalProducts = Product::count();
        $totalVendors = Vendor::count();
        $totalCustomers = Customer::count();

        $totalPoThisMonth = PurchaseOrder::whereMonth('date', $now->month)->whereYear('date', $now->year)->count();
        $totalSoThisMonth = SalesOrder::whereMonth('date', $now->month)->whereYear('date', $now->year)->count();

        $totalPurchases = PurchaseInvoice::whereMonth('date', $now->month)->whereYear('date', $now->year)->sum('total');
        $totalSales = SalesInvoice::whereMonth('date', $now->month)->whereYear('date', $now->year)->sum('total');
        $totalReceipts = Receipt::whereMonth('date', $now->month)->whereYear('date', $now->year)->sum('amount');

        $latestPOs = PurchaseOrder::with('vendor')->latest()->take(5)->get();
        $latestSOs = SalesOrder::with('customer')->latest()->take(5)->get();

        $pendingPOs = PurchaseOrder::whereNotIn('status', ['completed', 'cancelled'])->count();
        $pendingSOs = SalesOrder::whereNotIn('status', ['completed', 'cancelled'])->count();
        $unpaidInvoices = SalesInvoice::where('status', 'draft')->count();

        return view('dashboard', compact(
            'totalProducts', 'totalVendors', 'totalCustomers',
            'totalPoThisMonth', 'totalSoThisMonth',
            'totalPurchases', 'totalSales', 'totalReceipts',
            'latestPOs', 'latestSOs',
            'pendingPOs', 'pendingSOs', 'unpaidInvoices'
        ));
    }
}
