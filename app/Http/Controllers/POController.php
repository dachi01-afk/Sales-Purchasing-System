<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TPo;
use App\Models\TPermintaan;
use App\Models\MVendor;
use App\Models\MBarang;
use Illuminate\Support\Facades\Auth;

class POController extends Controller
{
    public function index()
    {
        $pos = TPo::with('details', 'permintaan', 'vendor', 'creator')->latest()->paginate(10);

        return view('purchasing.po.index', compact('pos'));
    }

    public function create()
    {
        $permintaans = TPermintaan::where('status', 'disetujui')->with('details')->get();
        $vendors = MVendor::all();
        $barangs = MBarang::all();

        return view('purchasing.po.create', compact('permintaans', 'vendors', 'barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_permintaan' => 'required|exists:t_permintaan,id_permintaan',
            'id_vendor' => 'required|exists:m_vendor,id_vendor',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $po = TPo::create([
            'id_permintaan' => $validated['id_permintaan'],
            'id_vendor' => $validated['id_vendor'],
            'tanggal' => $validated['tanggal'],
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $po->details()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);
        }

        return redirect()->route('po.index')
            ->with('success', 'Purchase Order berhasil dibuat');
    }

    public function edit(TPo $po)
    {
        $po->load('details');
        $permintaans = TPermintaan::where('status', 'disetujui')->with('details')->get();
        $vendors = MVendor::all();
        $barangs = MBarang::all();

        return view('purchasing.po.edit', compact('po', 'permintaans', 'vendors', 'barangs'));
    }

    public function update(Request $request, TPo $po)
    {
        $validated = $request->validate([
            'id_permintaan' => 'required|exists:t_permintaan,id_permintaan',
            'id_vendor' => 'required|exists:m_vendor,id_vendor',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $po->update([
            'id_permintaan' => $validated['id_permintaan'],
            'id_vendor' => $validated['id_vendor'],
            'tanggal' => $validated['tanggal'],
        ]);

        $po->details()->delete();

        foreach ($validated['items'] as $item) {
            $po->details()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);
        }

        return redirect()->route('po.index')
            ->with('success', 'Purchase Order berhasil diupdate');
    }

    public function destroy(TPo $po)
    {
        $po->delete();

        return redirect()->route('po.index')
            ->with('success', 'Purchase Order berhasil dihapus');
    }
}
