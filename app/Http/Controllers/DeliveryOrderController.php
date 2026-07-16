<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        $deliveryOrders = DeliveryOrder::with('items.product', 'salesOrder.customer', 'creator')->latest()->paginate(10);
        return view('delivery-orders.index', compact('deliveryOrders'));
    }

    public function create()
    {
        $salesOrders = SalesOrder::with('items.product', 'customer')
            ->whereIn('status', ['sent', 'completed'])
            ->whereDoesntHave('deliveryOrder')
            ->get();
        return view('delivery-orders.create', compact('salesOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'date' => 'required|date',
            'shipping_address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $deliveryOrder = DeliveryOrder::create([
            'sales_order_id' => $validated['sales_order_id'],
            'date' => $validated['date'],
            'shipping_address' => $validated['shipping_address'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $deliveryOrder->items()->create($item);
        }

        return redirect()->route('delivery-orders.index')->with('success', 'Delivery Order created successfully');
    }

    public function show(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('items.product', 'salesOrder.customer', 'creator');
        return view('delivery-orders.show', compact('deliveryOrder'));
    }

    public function edit(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('items');
        $salesOrders = SalesOrder::with('items.product', 'customer')->whereIn('status', ['sent', 'completed'])->get();
        return view('delivery-orders.edit', compact('deliveryOrder', 'salesOrders'));
    }

    public function update(Request $request, DeliveryOrder $deliveryOrder)
    {
        $validated = $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'date' => 'required|date',
            'shipping_address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $deliveryOrder->update([
            'sales_order_id' => $validated['sales_order_id'],
            'date' => $validated['date'],
            'shipping_address' => $validated['shipping_address'],
        ]);

        $deliveryOrder->items()->delete();
        foreach ($validated['items'] as $item) {
            $deliveryOrder->items()->create($item);
        }

        return redirect()->route('delivery-orders.index')->with('success', 'Delivery Order updated successfully');
    }

    public function destroy(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->delete();
        return redirect()->route('delivery-orders.index')->with('success', 'Delivery Order deleted successfully');
    }
}
