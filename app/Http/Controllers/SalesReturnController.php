<?php

namespace App\Http\Controllers;

use App\Models\SalesReturn;
use App\Models\DeliveryOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReturnController extends Controller
{
    public function index()
    {
        $returns = SalesReturn::with('items.product', 'deliveryOrder.salesOrder.customer', 'creator')->latest()->paginate(10);
        return view('sales-returns.index', compact('returns'));
    }

    public function create()
    {
        $deliveryOrders = DeliveryOrder::with('items.product', 'salesOrder.customer')
            ->whereDoesntHave('salesReturn')
            ->get();
        return view('sales-returns.create', compact('deliveryOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_order_id' => 'required|exists:delivery_orders,id',
            'date' => 'required|date',
            'reason' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.reason' => 'nullable|string',
        ]);

        $return = SalesReturn::create([
            'delivery_order_id' => $validated['delivery_order_id'],
            'date' => $validated['date'],
            'reason' => $validated['reason'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $return->items()->create($item);
        }

        return redirect()->route('sales-returns.index')->with('success', 'Return recorded successfully');
    }

    public function show(SalesReturn $salesReturn)
    {
        $salesReturn->load('items.product', 'deliveryOrder.salesOrder.customer', 'creator');
        return view('sales-returns.show', compact('salesReturn'));
    }

    public function edit(SalesReturn $salesReturn)
    {
        $salesReturn->load('items');
        $deliveryOrders = DeliveryOrder::with('items.product', 'salesOrder.customer')->get();
        return view('sales-returns.edit', compact('salesReturn', 'deliveryOrders'));
    }

    public function update(Request $request, SalesReturn $salesReturn)
    {
        $validated = $request->validate([
            'delivery_order_id' => 'required|exists:delivery_orders,id',
            'date' => 'required|date',
            'reason' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.reason' => 'nullable|string',
        ]);

        $salesReturn->update([
            'delivery_order_id' => $validated['delivery_order_id'],
            'date' => $validated['date'],
            'reason' => $validated['reason'],
        ]);

        $salesReturn->items()->delete();
        foreach ($validated['items'] as $item) {
            $salesReturn->items()->create($item);
        }

        return redirect()->route('sales-returns.index')->with('success', 'Return updated successfully');
    }

    public function destroy(SalesReturn $salesReturn)
    {
        $salesReturn->delete();
        return redirect()->route('sales-returns.index')->with('success', 'Return deleted successfully');
    }
}
