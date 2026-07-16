<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseReturn;
use App\Models\SalesOrder;
use App\Models\DeliveryOrder;
use App\Models\SalesInvoice;
use App\Models\SalesReturn;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function purchases(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $purchaseOrders = PurchaseOrder::with('vendor', 'items')
            ->when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->latest()
            ->paginate(10);

        $totalPO = PurchaseOrder::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalReceipts = GoodsReceipt::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalInvoice = PurchaseInvoice::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->sum('total');

        return view('reports.purchases', compact('purchaseOrders', 'totalPO', 'totalReceipts', 'totalInvoice', 'start', 'end'));
    }

    public function sales(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $salesOrders = SalesOrder::with('customer', 'items')
            ->when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->latest()
            ->paginate(10);

        $totalSO = SalesOrder::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalDO = DeliveryOrder::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalInvoice = SalesInvoice::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->sum('total');

        $totalReceipts = Receipt::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->sum('amount');

        return view('reports.sales', compact('salesOrders', 'totalSO', 'totalDO', 'totalInvoice', 'totalReceipts', 'start', 'end'));
    }

    public function financial(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $totalPurchases = PurchaseInvoice::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->sum('total');

        $totalSales = SalesInvoice::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->sum('total');

        $totalPurchaseReturns = PurchaseReturn::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalSalesReturns = SalesReturn::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalReceipts = Receipt::when($start, fn($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
            ->sum('amount');

        return view('reports.financial', compact(
            'totalPurchases', 'totalSales',
            'totalPurchaseReturns', 'totalSalesReturns',
            'totalReceipts', 'start', 'end'
        ));
    }
}
