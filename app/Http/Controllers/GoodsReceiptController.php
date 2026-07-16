<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoodsReceiptController extends Controller
{
    public function index()
    {
        $goodsReceipts = GoodsReceipt::with('items', 'purchaseOrder', 'creator')->latest()->paginate(10);
        return view('goods-receipts.index', compact('goodsReceipts'));
    }

    public function create()
    {
        $purchaseOrders = PurchaseOrder::with('items.product')->whereIn('status', ['sent', 'completed'])->get();
        $products = Product::all();
        return view('goods-receipts.create', compact('purchaseOrders', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty_received' => 'required|integer|min:0',
        ]);

        $goodsReceipt = GoodsReceipt::create([
            'purchase_order_id' => $validated['purchase_order_id'],
            'date' => $validated['date'],
            'notes' => $validated['notes'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $goodsReceipt->items()->create($item);
        }

        return redirect()->route('goods-receipts.index')
            ->with('success', 'Goods receipt recorded successfully');
    }

    public function edit(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load('items');
        $purchaseOrders = PurchaseOrder::with('items.product')->whereIn('status', ['sent', 'completed'])->get();
        $products = Product::all();
        return view('goods-receipts.edit', compact('goodsReceipt', 'purchaseOrders', 'products'));
    }

    public function update(Request $request, GoodsReceipt $goodsReceipt)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty_received' => 'required|integer|min:0',
        ]);

        $goodsReceipt->update([
            'purchase_order_id' => $validated['purchase_order_id'],
            'date' => $validated['date'],
            'notes' => $validated['notes'],
        ]);

        $goodsReceipt->items()->delete();

        foreach ($validated['items'] as $item) {
            $goodsReceipt->items()->create($item);
        }

        return redirect()->route('goods-receipts.index')
            ->with('success', 'Goods receipt updated successfully');
    }

    public function destroy(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->delete();
        return redirect()->route('goods-receipts.index')
            ->with('success', 'Goods receipt deleted successfully');
    }
}
