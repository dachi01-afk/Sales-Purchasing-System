<?php

namespace App\Http\Controllers;

use App\Models\TDo;
use App\Models\TSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DOController extends Controller
{
    public function index()
    {
        $dos = TDo::with('details.barang', 'so.customer', 'creator')->latest()->paginate(10);
        return view('sales.do.index', compact('dos'));
    }

    public function create()
    {
        $sos = TSo::with('details.barang', 'customer')
            ->whereIn('status', ['dikirim', 'selesai'])
            ->whereDoesntHave('do')
            ->get();
        return view('sales.do.create', compact('sos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_so' => 'required|exists:t_so,id_so',
            'tanggal' => 'required|date',
            'alamat_pengiriman' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty_dikirim' => 'required|integer|min:1',
        ]);

        $do = TDo::create([
            'id_so' => $validated['id_so'],
            'tanggal' => $validated['tanggal'],
            'alamat_pengiriman' => $validated['alamat_pengiriman'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $do->details()->create($item);
        }

        return redirect()->route('do.index')->with('success', 'Delivery Order berhasil dibuat');
    }

    public function show(TDo $do)
    {
        $do->load('details.barang', 'so.customer', 'creator');
        return view('sales.do.show', compact('do'));
    }

    public function edit(TDo $do)
    {
        $do->load('details');
        $sos = TSo::with('details.barang', 'customer')->whereIn('status', ['dikirim', 'selesai'])->get();
        return view('sales.do.edit', compact('do', 'sos'));
    }

    public function update(Request $request, TDo $do)
    {
        $validated = $request->validate([
            'id_so' => 'required|exists:t_so,id_so',
            'tanggal' => 'required|date',
            'alamat_pengiriman' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty_dikirim' => 'required|integer|min:1',
        ]);

        $do->update([
            'id_so' => $validated['id_so'],
            'tanggal' => $validated['tanggal'],
            'alamat_pengiriman' => $validated['alamat_pengiriman'],
        ]);

        $do->details()->delete();
        foreach ($validated['items'] as $item) {
            $do->details()->create($item);
        }

        return redirect()->route('do.index')->with('success', 'Delivery Order berhasil diupdate');
    }

    public function destroy(TDo $do)
    {
        $do->delete();
        return redirect()->route('do.index')->with('success', 'Delivery Order berhasil dihapus');
    }
}