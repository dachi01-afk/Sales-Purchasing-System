<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with('salesInvoice.deliveryOrder.salesOrder.customer', 'creator')->latest()->paginate(10);
        return view('receipts.index', compact('receipts'));
    }

    public function create()
    {
        $invoices = SalesInvoice::with('deliveryOrder.salesOrder.customer')
            ->where('status', 'paid')
            ->whereDoesntHave('receipt')
            ->get();
        return view('receipts.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sales_invoice_id' => 'required|exists:sales_invoices,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Receipt::create([
            'sales_invoice_id' => $validated['sales_invoice_id'],
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'notes' => $validated['notes'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('receipts.index')->with('success', 'Receipt created successfully');
    }

    public function show(Receipt $receipt)
    {
        $receipt->load('salesInvoice.deliveryOrder.salesOrder.customer', 'creator');
        return view('receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt)
    {
        $invoices = SalesInvoice::with('deliveryOrder.salesOrder.customer')->where('status', 'paid')->get();
        return view('receipts.edit', compact('receipt', 'invoices'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'sales_invoice_id' => 'required|exists:sales_invoices,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $receipt->update($validated);

        return redirect()->route('receipts.index')->with('success', 'Receipt updated successfully');
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('receipts.index')->with('success', 'Receipt deleted successfully');
    }
}
