<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::with('items.product', 'customer', 'creator')->latest()->paginate(10);
        return view('sales-orders.index', compact('salesOrders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('sales-orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'status' => 'required|in:draft,sent,completed',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['price']);

        $salesOrder = SalesOrder::create([
            'customer_id' => $validated['customer_id'],
            'date' => $validated['date'],
            'total' => $total,
            'status' => $validated['status'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $salesOrder->items()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price'],
            ]);
        }

        return redirect()->route('sales-orders.index')->with('success', 'Sales Order created successfully');
    }

    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load('items.product', 'customer', 'creator');
        return view('sales-orders.show', compact('salesOrder'));
    }

    public function edit(SalesOrder $salesOrder)
    {
        $salesOrder->load('items');
        $customers = Customer::all();
        $products = Product::all();
        return view('sales-orders.edit', compact('salesOrder', 'customers', 'products'));
    }

    public function update(Request $request, SalesOrder $salesOrder)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'status' => 'required|in:draft,sent,completed',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['price']);

        $salesOrder->update([
            'customer_id' => $validated['customer_id'],
            'date' => $validated['date'],
            'total' => $total,
            'status' => $validated['status'],
        ]);

        $salesOrder->items()->delete();
        foreach ($validated['items'] as $item) {
            $salesOrder->items()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price'],
            ]);
        }

        return redirect()->route('sales-orders.index')->with('success', 'Sales Order updated successfully');
    }

    public function destroy(SalesOrder $salesOrder)
    {
        $salesOrder->delete();
        return redirect()->route('sales-orders.index')->with('success', 'Sales Order deleted successfully');
    }
}
