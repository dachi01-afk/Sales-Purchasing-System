<?php

namespace App\Http\Controllers;

use App\Models\TReturPurchasing;
use App\Models\TPenerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturPurchasingController extends Controller
{
    public function index()
    {
        $returs = TReturPurchasing::with('details.barang', 'penerimaan.po.vendor', 'creator')->latest()->paginate(10);
        return view('purchasing.retur.index', compact('returs'));
    }

    public function create()
    {
        $penerimaans = TPenerimaan::with('details.barang', 'po.vendor')
            ->whereDoesntHave('returPurchasing')
            ->get();
        return view('purchasing.retur.create', compact('penerimaans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_penerimaan' => 'required|exists:t_penerimaan,id_penerimaan',
            'tanggal' => 'required|date',
            'alasan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty_retur' => 'required|integer|min:1',
            'items.*.alasan_item' => 'nullable|string',
        ]);

        $retur = TReturPurchasing::create([
            'id_penerimaan' => $validated['id_penerimaan'],
            'tanggal' => $validated['tanggal'],
            'alasan' => $validated['alasan'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $retur->details()->create($item);
        }

        return redirect()->route('retur-purchasing.index')
            ->with('success', 'Retur berhasil dicatat');
    }

    public function show(TReturPurchasing $returPurchasing)
    {
        $returPurchasing->load('details.barang', 'penerimaan.po.vendor', 'creator');
        return view('purchasing.retur.show', compact('returPurchasing'));
    }

    public function edit(TReturPurchasing $returPurchasing)
    {
        $returPurchasing->load('details');
        $penerimaans = TPenerimaan::with('details.barang', 'po.vendor')->get();
        return view('purchasing.retur.edit', compact('returPurchasing', 'penerimaans'));
    }

    public function update(Request $request, TReturPurchasing $returPurchasing)
    {
        $validated = $request->validate([
            'id_penerimaan' => 'required|exists:t_penerimaan,id_penerimaan',
            'tanggal' => 'required|date',
            'alasan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty_retur' => 'required|integer|min:1',
            'items.*.alasan_item' => 'nullable|string',
        ]);

        $returPurchasing->update([
            'id_penerimaan' => $validated['id_penerimaan'],
            'tanggal' => $validated['tanggal'],
            'alasan' => $validated['alasan'],
        ]);

        $returPurchasing->details()->delete();

        foreach ($validated['items'] as $item) {
            $returPurchasing->details()->create($item);
        }

        return redirect()->route('retur-purchasing.index')
            ->with('success', 'Retur berhasil diupdate');
    }

    public function destroy(TReturPurchasing $returPurchasing)
    {
        $returPurchasing->delete();
        return redirect()->route('retur-purchasing.index')
            ->with('success', 'Retur berhasil dihapus');
    }
}