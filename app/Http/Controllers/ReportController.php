<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\GoodsReceipt;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\Receipt;
use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use App\Models\SalesReturn;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function purchases(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $purchaseOrders = PurchaseOrder::with('vendor', 'items')
            ->when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->latest()
            ->paginate(10);

        $totalPO = PurchaseOrder::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalReceipts = GoodsReceipt::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalInvoice = PurchaseInvoice::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->sum('total');

        return view('reports.purchases', compact('purchaseOrders', 'totalPO', 'totalReceipts', 'totalInvoice', 'start', 'end'));
    }

    public function sales(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $salesOrders = SalesOrder::with('customer', 'items')
            ->when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->latest()
            ->paginate(10);

        $totalSO = SalesOrder::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalDO = DeliveryOrder::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalInvoice = SalesInvoice::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->sum('total');

        $totalReceipts = Receipt::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->sum('amount');

        return view('reports.sales', compact('salesOrders', 'totalSO', 'totalDO', 'totalInvoice', 'totalReceipts', 'start', 'end'));
    }

    public function financial(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $totalPurchases = PurchaseInvoice::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->sum('total');

        $totalSales = SalesInvoice::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->sum('total');

        $totalPurchaseReturns = PurchaseReturn::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalSalesReturns = SalesReturn::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->count();

        $totalReceipts = Receipt::when($start, fn ($q) => $q->whereDate('date', '>=', $start))
            ->when($end, fn ($q) => $q->whereDate('date', '<=', $end))
            ->sum('amount');

        return view('reports.financial', compact(
            'totalPurchases', 'totalSales',
            'totalPurchaseReturns', 'totalSalesReturns',
            'totalReceipts', 'start', 'end'
        ));
    }

    // ==================== DATA TABLES (YAJRA) ====================

    public function products()
    {
        return view('reports.products');
    }

    public function productsData()
    {
        return DataTables::of(Product::query())
            ->editColumn('standard_price', fn ($p) => 'Rp '.number_format($p->standard_price, 0, ',', '.'))
            ->make(true);
    }

    public function purchasesData(Request $request)
    {
        $query = PurchaseOrder::with('vendor', 'items')
            ->when($request->start, fn ($q) => $q->whereDate('date', '>=', $request->start))
            ->when($request->end, fn ($q) => $q->whereDate('date', '<=', $request->end))
            ->latest();
        

        return DataTables::of($query)
            ->addColumn('vendor_name', fn ($po) => $po->vendor->name)
            ->addColumn('items_count', fn ($po) => $po->items->count().' item(s)')
            ->addColumn('po_id', fn ($po) => '#'.$po->id)
            ->editColumn('date', fn ($po) => $po->date->format('d/m/Y'))
            ->rawColumns(['po_id'])
            ->make(true);
    }

    public function salesData(Request $request)
    {
        $query = SalesOrder::with('customer', 'items')
            ->when($request->start, fn ($q) => $q->whereDate('date', '>=', $request->start))
            ->when($request->end, fn ($q) => $q->whereDate('date', '<=', $request->end))
            ->latest();

        return DataTables::of($query)
            ->addColumn('customer_name', fn ($so) => $so->customer->name)
            ->addColumn('items_count', fn ($so) => $so->items->count().' item(s)')
            ->addColumn('so_id', fn ($so) => '#'.$so->id)
            ->editColumn('date', fn ($so) => $so->date->format('d/m/Y'))
            ->rawColumns(['so_id'])
            ->make(true);
    }
}
