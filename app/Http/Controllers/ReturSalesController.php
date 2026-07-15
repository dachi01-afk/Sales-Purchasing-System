<?php

namespace App\Http\Controllers;

use App\Models\TReturSales;
use App\Models\TDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturSalesController extends Controller
{
    public function index()
    {
        $returs = TReturSales::with('details.barang', 'do.so.customer', 'creator')->latest()->paginate(10);
        return view('sales.retur.index', compact('returs'));
    }

    public function create()
    {
        $dos = TDo::with('details.barang', 'so.customer')
            ->whereDoesntHave('returSales')
            ->get();
        return view('sales.retur.create', compact('dos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_do' => 'required|exists:t_do,id_do',
            'tanggal' => 'required|date',
            'alasan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty_retur' => 'required|integer|min:1',
            'items.*.alasan_item' => 'nullable|string',
        ]);

        $retur = TReturSales::create([
            'id_do' => $validated['id_do'],
            'tanggal' => $validated['tanggal'],
            'alasan' => $validated['alasan'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $retur->details()->create($item);
        }

        return redirect()->route('retur-sales.index')->with('success', 'Retur Sales berhasil dicatat');
    }

    public function show(TReturSales $returSale)
    {
        $returSale->load('details.barang', 'do.so.customer', 'creator');
        return view('sales.retur.show', compact('returSale'));
    }

    public function edit(TReturSales $returSale)
    {
        $returSale->load('details');
        $dos = TDo::with('details.barang', 'so.customer')->get();
        return view('sales.retur.edit', compact('returSales', 'dos'));
    }

    public function update(Request $request, TReturSales $returSale)
    {
        $validated = $request->validate([
            'id_do' => 'required|exists:t_do,id_do',
            'tanggal' => 'required|date',
            'alasan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty_retur' => 'required|integer|min:1',
            'items.*.alasan_item' => 'nullable|string',
        ]);

        $returSale->update([
            'id_do' => $validated['id_do'],
            'tanggal' => $validated['tanggal'],
            'alasan' => $validated['alasan'],
        ]);

        $returSale->details()->delete();
        foreach ($validated['items'] as $item) {
            $returSale->details()->create($item);
        }

        return redirect()->route('retur-sales.index')->with('success', 'Retur Sales berhasil diupdate');
    }

    public function destroy(TReturSales $returSale)
    {
        $returSale->delete();
        return redirect()->route('retur-sales.index')->with('success', 'Retur Sales berhasil dihapus');
    }
}