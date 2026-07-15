<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TPermintaan;
use App\Models\MBarang;
use Illuminate\Support\Facades\Auth;

class PermintaanController extends Controller
{
    public function index()
    {
        $permintaans = TPermintaan::with('details', 'creator')->latest()->paginate(10);
        return view('purchasing.permintaan.index', compact('permintaans'));
    }

    public function create()
    {
        $barangs = MBarang::all();
        return view('purchasing.permintaan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.keterangan' => 'nullable|string',
        ]);

        $permintaan = TPermintaan::create([
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $permintaan->details()->create($item);
        }

        return redirect()->route('permintaan.index')
            ->with('success', 'Permintaan pembelian berhasil dibuat');
    }

    public function edit(TPermintaan $permintaan)
    {
        $permintaan->load('details');
        $barangs = MBarang::all();
        return view('purchasing.permintaan.edit', compact('permintaan', 'barangs'));
    }

    public function update(Request $request, TPermintaan $permintaan)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.keterangan' => 'nullable|string',
        ]);

        $permintaan->update([
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
        ]);

        $permintaan->details()->delete();

        foreach ($validated['items'] as $item) {
            $permintaan->details()->create($item);
        }

        return redirect()->route('permintaan.index')
            ->with('success', 'Permintaan pembelian berhasil diupdate');
    }

    public function destroy(TPermintaan $permintaan)
    {
        $permintaan->delete();
        return redirect()->route('permintaan.index')
            ->with('success', 'Permintaan pembelian berhasil dihapus');
    }
}
