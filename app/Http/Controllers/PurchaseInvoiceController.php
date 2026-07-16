<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseInvoiceController extends Controller
{
    public function index()
    {
        $invoices = PurchaseInvoice::with('items.product', 'purchaseOrder.vendor', 'creator')->latest()->paginate(10);
        return view('purchase-invoices.index', compact('invoices'));
    }

    public function create()
    {
        $purchaseOrders = PurchaseOrder::with('items.product', 'vendor')
            ->whereIn('status', ['completed'])
            ->whereDoesntHave('purchaseInvoice')
            ->get();
        return view('purchase-invoices.create', compact('purchaseOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'date' => 'required|date',
            'status' => 'required|in:draft,paid',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['price']);

        $invoice = PurchaseInvoice::create([
            'purchase_order_id' => $validated['purchase_order_id'],
            'date' => $validated['date'],
            'total' => $total,
            'status' => $validated['status'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price'],
            ]);
        }

        return redirect()->route('purchase-invoices.index')
            ->with('success', 'Invoice created successfully');
    }

    public function show(PurchaseInvoice $purchaseInvoice)
    {
        $purchaseInvoice->load('items.product', 'purchaseOrder.vendor', 'creator');
        return view('purchase-invoices.show', compact('purchaseInvoice'));
    }

    public function edit(PurchaseInvoice $purchaseInvoice)
    {
        $purchaseInvoice->load('items');
        $purchaseOrders = PurchaseOrder::with('items.product', 'vendor')->whereIn('status', ['completed'])->get();
        return view('purchase-invoices.edit', compact('purchaseInvoice', 'purchaseOrders'));
    }

    public function update(Request $request, PurchaseInvoice $purchaseInvoice)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'date' => 'required|date',
            'status' => 'required|in:draft,paid',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['price']);

        $purchaseInvoice->update([
            'purchase_order_id' => $validated['purchase_order_id'],
            'date' => $validated['date'],
            'total' => $total,
            'status' => $validated['status'],
        ]);

        $purchaseInvoice->items()->delete();

        foreach ($validated['items'] as $item) {
            $purchaseInvoice->items()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price'],
            ]);
        }

        return redirect()->route('purchase-invoices.index')
            ->with('success', 'Invoice updated successfully');
    }

    public function destroy(PurchaseInvoice $purchaseInvoice)
    {
        $purchaseInvoice->delete();
        return redirect()->route('purchase-invoices.index')
            ->with('success', 'Invoice deleted successfully');
    }
}
