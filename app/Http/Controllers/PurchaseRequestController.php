<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $purchaseRequests = PurchaseRequest::with('items', 'creator')->latest()->paginate(10);
        return view('purchase-requests.index', compact('purchaseRequests'));
    }

    public function create()
    {
        $products = Product::all();
        return view('purchase-requests.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        $purchaseRequest = PurchaseRequest::create([
            'date' => $validated['date'],
            'notes' => $validated['notes'],
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $purchaseRequest->items()->create($item);
        }

        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase request created successfully');
    }

    public function edit(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load('items');
        $products = Product::all();
        return view('purchase-requests.edit', compact('purchaseRequest', 'products'));
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        $purchaseRequest->update([
            'date' => $validated['date'],
            'notes' => $validated['notes'],
        ]);

        $purchaseRequest->items()->delete();

        foreach ($validated['items'] as $item) {
            $purchaseRequest->items()->create($item);
        }

        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase request updated successfully');
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->delete();
        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase request deleted successfully');
    }
}
