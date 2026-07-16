<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('items', 'purchaseRequest', 'vendor', 'creator')->latest()->paginate(10);

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $purchaseRequests = PurchaseRequest::where('status', 'approved')->with('items')->get();
        $vendors = Vendor::all();
        $products = Product::all();

        return view('purchase-orders.create', compact('purchaseRequests', 'vendors', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_request_id' => 'required|exists:purchase_requests,id',
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'purchase_request_id' => $validated['purchase_request_id'],
            'vendor_id' => $validated['vendor_id'],
            'date' => $validated['date'],
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $purchaseOrder->items()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price'],
            ]);
        }

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order created successfully');
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items');
        $purchaseRequests = PurchaseRequest::where('status', 'approved')->with('items')->get();
        $vendors = Vendor::all();
        $products = Product::all();

        return view('purchase-orders.edit', compact('purchaseOrder', 'purchaseRequests', 'vendors', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'purchase_request_id' => 'required|exists:purchase_requests,id',
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $purchaseOrder->update([
            'purchase_request_id' => $validated['purchase_request_id'],
            'vendor_id' => $validated['vendor_id'],
            'date' => $validated['date'],
        ]);

        $purchaseOrder->items()->delete();

        foreach ($validated['items'] as $item) {
            $purchaseOrder->items()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price'],
            ]);
        }

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order updated successfully');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order deleted successfully');
    }
}
