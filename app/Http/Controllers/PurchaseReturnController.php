<?php

namespace App\Http\Controllers;

use App\Models\PurchaseReturn;
use App\Models\GoodsReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $returns = PurchaseReturn::with('items.product', 'goodsReceipt.purchaseOrder.vendor', 'creator')->latest()->paginate(10);
        return view('purchase-returns.index', compact('returns'));
    }

    public function create()
    {
        $goodsReceipts = GoodsReceipt::with('items.product', 'purchaseOrder.vendor')
            ->whereDoesntHave('purchaseReturn')
            ->get();
        return view('purchase-returns.create', compact('goodsReceipts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'goods_receipt_id' => 'required|exists:goods_receipts,id',
            'date' => 'required|date',
            'reason' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.reason' => 'nullable|string',
        ]);

        $return = PurchaseReturn::create([
            'goods_receipt_id' => $validated['goods_receipt_id'],
            'date' => $validated['date'],
            'reason' => $validated['reason'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $return->items()->create($item);
        }

        return redirect()->route('purchase-returns.index')
            ->with('success', 'Return recorded successfully');
    }

    public function show(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load('items.product', 'goodsReceipt.purchaseOrder.vendor', 'creator');
        return view('purchase-returns.show', compact('purchaseReturn'));
    }

    public function edit(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load('items');
        $goodsReceipts = GoodsReceipt::with('items.product', 'purchaseOrder.vendor')->get();
        return view('purchase-returns.edit', compact('purchaseReturn', 'goodsReceipts'));
    }

    public function update(Request $request, PurchaseReturn $purchaseReturn)
    {
        $validated = $request->validate([
            'goods_receipt_id' => 'required|exists:goods_receipts,id',
            'date' => 'required|date',
            'reason' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.reason' => 'nullable|string',
        ]);

        $purchaseReturn->update([
            'goods_receipt_id' => $validated['goods_receipt_id'],
            'date' => $validated['date'],
            'reason' => $validated['reason'],
        ]);

        $purchaseReturn->items()->delete();

        foreach ($validated['items'] as $item) {
            $purchaseReturn->items()->create($item);
        }

        return redirect()->route('purchase-returns.index')
            ->with('success', 'Return updated successfully');
    }

    public function destroy(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->delete();
        return redirect()->route('purchase-returns.index')
            ->with('success', 'Return deleted successfully');
    }
}
