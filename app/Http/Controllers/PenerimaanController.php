<?php

namespace App\Http\Controllers;

use App\Models\TPenerimaan;
use App\Models\TPenerimaanDetail;
use App\Models\TPo;
use App\Models\MBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerimaanController extends Controller
{
    public function index()
    {
        $penerimaans = TPenerimaan::with('details', 'po', 'creator')->latest()->paginate(10);
        return view('purchasing.penerimaan.index', compact('penerimaans'));
    }

    public function create()
    {
        $pos = TPo::with('details.barang')->whereIn('status', ['dikirim', 'selesai'])->get();
        $barangs = MBarang::all();
        return view('purchasing.penerimaan.create', compact('pos', 'barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_po' => 'required|exists:t_po,id_po',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty_diterima' => 'required|integer|min:0',
        ]);

        $penerimaan = TPenerimaan::create([
            'id_po' => $validated['id_po'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $penerimaan->details()->create($item);
        }

        return redirect()->route('penerimaan.index')
            ->with('success', 'Penerimaan barang berhasil dicatat');
    }

    public function edit(TPenerimaan $penerimaan)
    {
        $penerimaan->load('details');
        $pos = TPo::with('details.barang')->whereIn('status', ['dikirim', 'selesai'])->get();
        $barangs = MBarang::all();
        return view('purchasing.penerimaan.edit', compact('penerimaan', 'pos', 'barangs'));
    }

    public function update(Request $request, TPenerimaan $penerimaan)
    {
        $validated = $request->validate([
            'id_po' => 'required|exists:t_po,id_po',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty_diterima' => 'required|integer|min:0',
        ]);

        $penerimaan->update([
            'id_po' => $validated['id_po'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
        ]);

        $penerimaan->details()->delete();

        foreach ($validated['items'] as $item) {
            $penerimaan->details()->create($item);
        }

        return redirect()->route('penerimaan.index')
            ->with('success', 'Penerimaan barang berhasil diupdate');
    }

    public function destroy(TPenerimaan $penerimaan)
    {
        $penerimaan->delete();
        return redirect()->route('penerimaan.index')
            ->with('success', 'Penerimaan barang berhasil dihapus');
    }
}