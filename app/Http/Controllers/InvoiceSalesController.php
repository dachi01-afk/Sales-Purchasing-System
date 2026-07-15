<?php

namespace App\Http\Controllers;

use App\Models\TInvoiceSales;
use App\Models\TDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceSalesController extends Controller
{
    public function index()
    {
        $invoices = TInvoiceSales::with('details.barang', 'do.so.customer', 'creator')->latest()->paginate(10);
        return view('sales.invoice.index', compact('invoices'));
    }

    public function create()
    {
        $dos = TDo::with('details.barang', 'so.customer')
            ->whereDoesntHave('invoiceSales')
            ->get();
        return view('sales.invoice.create', compact('dos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_do' => 'required|exists:t_do,id_do',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,lunas',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['harga']);

        $invoice = TInvoiceSales::create([
            'id_do' => $validated['id_do'],
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

        return redirect()->route('invoice-sales.index')->with('success', 'Invoice Sales berhasil dibuat');
    }

    public function show(TInvoiceSales $invoiceSale)
    {
        $invoiceSale->load('details.barang', 'do.so.customer', 'creator');
        return view('sales.invoice.show', compact('invoiceSale'));
    }

    public function edit(TInvoiceSales $invoiceSale)
    {
        $invoiceSale->load('details');
        $dos = TDo::with('details.barang', 'so.customer')->get();
        return view('sales.invoice.edit', compact('invoiceSales', 'dos'));
    }

    public function update(Request $request, TInvoiceSales $invoiceSale)
    {
        $validated = $request->validate([
            'id_do' => 'required|exists:t_do,id_do',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,lunas',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['harga']);

        $invoiceSale->update([
            'id_do' => $validated['id_do'],
            'tanggal' => $validated['tanggal'],
            'total' => $total,
            'status' => $validated['status'],
        ]);

        $invoiceSale->details()->delete();
        foreach ($validated['items'] as $item) {
            $invoiceSale->details()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);
        }

        return redirect()->route('invoice-sales.index')->with('success', 'Invoice Sales berhasil diupdate');
    }

    public function destroy(TInvoiceSales $invoiceSale)
    {
        $invoiceSale->delete();
        return redirect()->route('invoice-sales.index')->with('success', 'Invoice Sales berhasil dihapus');
    }
}