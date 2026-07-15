<?php

namespace App\Http\Controllers;

use App\Models\TInvoicePurchasing;
use App\Models\TPo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicePurchasingController extends Controller
{
    public function index()
    {
        $invoices = TInvoicePurchasing::with('details.barang', 'po.vendor', 'creator')->latest()->paginate(10);
        return view('purchasing.invoice.index', compact('invoices'));
    }

    public function create()
    {
        $pos = TPo::with('details.barang', 'vendor')
            ->whereIn('status', ['selesai'])
            ->whereDoesntHave('invoicePurchasing')
            ->get();
        return view('purchasing.invoice.create', compact('pos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_po' => 'required|exists:t_po,id_po',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,lunas',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['harga']);

        $invoice = TInvoicePurchasing::create([
            'id_po' => $validated['id_po'],
            'tanggal' => $validated['tanggal'],
            'total' => $total,
            'status' => $validated['status'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $invoice->details()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);
        }

        return redirect()->route('invoice-purchasing.index')
            ->with('success', 'Invoice berhasil dibuat');
    }

    public function show(TInvoicePurchasing $invoicePurchasing)
    {
        $invoicePurchasing->load('details.barang', 'po.vendor', 'creator');
        return view('purchasing.invoice.show', compact('invoicePurchasing'));
    }

    public function edit(TInvoicePurchasing $invoicePurchasing)
    {
        $invoicePurchasing->load('details');
        $pos = TPo::with('details.barang', 'vendor')->whereIn('status', ['selesai'])->get();
        return view('purchasing.invoice.edit', compact('invoicePurchasing', 'pos'));
    }

    public function update(Request $request, TInvoicePurchasing $invoicePurchasing)
    {
        $validated = $request->validate([
            'id_po' => 'required|exists:t_po,id_po',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,lunas',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['harga']);

        $invoicePurchasing->update([
            'id_po' => $validated['id_po'],
            'tanggal' => $validated['tanggal'],
            'total' => $total,
            'status' => $validated['status'],
        ]);

        $invoicePurchasing->details()->delete();

        foreach ($validated['items'] as $item) {
            $invoicePurchasing->details()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);
        }

        return redirect()->route('invoice-purchasing.index')
            ->with('success', 'Invoice berhasil diupdate');
    }

    public function destroy(TInvoicePurchasing $invoicePurchasing)
    {
        $invoicePurchasing->delete();
        return redirect()->route('invoice-purchasing.index')
            ->with('success', 'Invoice berhasil dihapus');
    }
}