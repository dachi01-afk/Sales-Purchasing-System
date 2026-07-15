<?php

namespace App\Http\Controllers;

use App\Models\TKwitansi;
use App\Models\TInvoiceSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KwitansiController extends Controller
{
    public function index()
    {
        $kwitansis = TKwitansi::with('invoiceSales.do.so.customer', 'creator')->latest()->paginate(10);
        return view('sales.kwitansi.index', compact('kwitansis'));
    }

    public function create()
    {
        $invoices = TInvoiceSales::with('do.so.customer')
            ->where('status', 'lunas')
            ->whereDoesntHave('kwitansi')
            ->get();
        return view('sales.kwitansi.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_invoice_sales' => 'required|exists:t_invoice_sales,id_invoice_sales',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        TKwitansi::create([
            'id_invoice_sales' => $validated['id_invoice_sales'],
            'tanggal' => $validated['tanggal'],
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('kwitansi.index')->with('success', 'Kwitansi berhasil dibuat');
    }

    public function show(TKwitansi $kwitansi)
    {
        $kwitansi->load('invoiceSales.do.so.customer', 'creator');
        return view('sales.kwitansi.show', compact('kwitansi'));
    }

    public function edit(TKwitansi $kwitansi)
    {
        $invoices = TInvoiceSales::with('do.so.customer')->where('status', 'lunas')->get();
        return view('sales.kwitansi.edit', compact('kwitansi', 'invoices'));
    }

    public function update(Request $request, TKwitansi $kwitansi)
    {
        $validated = $request->validate([
            'id_invoice_sales' => 'required|exists:t_invoice_sales,id_invoice_sales',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $kwitansi->update($validated);

        return redirect()->route('kwitansi.index')->with('success', 'Kwitansi berhasil diupdate');
    }

    public function destroy(TKwitansi $kwitansi)
    {
        $kwitansi->delete();
        return redirect()->route('kwitansi.index')->with('success', 'Kwitansi berhasil dihapus');
    }
}