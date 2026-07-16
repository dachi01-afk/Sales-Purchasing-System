<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\DeliveryOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesInvoiceController extends Controller
{
    public function index()
    {
        $invoices = SalesInvoice::with('items.product', 'deliveryOrder.salesOrder.customer', 'creator')->latest()->paginate(10);
        return view('sales-invoices.index', compact('invoices'));
    }

    public function create()
    {
        $deliveryOrders = DeliveryOrder::with('items.product', 'salesOrder.customer')
            ->whereDoesntHave('salesInvoice')
            ->get();
        return view('sales-invoices.create', compact('deliveryOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_order_id' => 'required|exists:delivery_orders,id',
            'date' => 'required|date',
            'status' => 'required|in:draft,paid',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['price']);

        $invoice = SalesInvoice::create([
            'delivery_order_id' => $validated['delivery_order_id'],
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

        return redirect()->route('sales-invoices.index')->with('success', 'Invoice created successfully');
    }

    public function show(SalesInvoice $salesInvoice)
    {
        $salesInvoice->load('items.product', 'deliveryOrder.salesOrder.customer', 'creator');
        return view('sales-invoices.show', compact('salesInvoice'));
    }

    public function edit(SalesInvoice $salesInvoice)
    {
        $salesInvoice->load('items');
        $deliveryOrders = DeliveryOrder::with('items.product', 'salesOrder.customer')->get();
        return view('sales-invoices.edit', compact('salesInvoice', 'deliveryOrders'));
    }

    public function update(Request $request, SalesInvoice $salesInvoice)
    {
        $validated = $request->validate([
            'delivery_order_id' => 'required|exists:delivery_orders,id',
            'date' => 'required|date',
            'status' => 'required|in:draft,paid',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['price']);

        $salesInvoice->update([
            'delivery_order_id' => $validated['delivery_order_id'],
            'date' => $validated['date'],
            'total' => $total,
            'status' => $validated['status'],
        ]);

        $salesInvoice->items()->delete();
        foreach ($validated['items'] as $item) {
            $salesInvoice->items()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price'],
            ]);
        }

        return redirect()->route('sales-invoices.index')->with('success', 'Invoice updated successfully');
    }

    public function destroy(SalesInvoice $salesInvoice)
    {
        $salesInvoice->delete();
        return redirect()->route('sales-invoices.index')->with('success', 'Invoice deleted successfully');
    }
}
