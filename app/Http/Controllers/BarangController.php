<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MBarang;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = MBarang::paginate(10);
        return view('master.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('master.barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:50|unique:m_barang,sku',
            'nama_barang' => 'required|string|max:255',
            'harga_standar' => 'required|numeric|min:0',
        ]);

        MBarang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(MBarang $barang)
    {
        return view('master.barang.edit', compact('barang'));
    }

    public function update(Request $request, MBarang $barang)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:50|unique:m_barang,sku,' . $barang->sku . ',sku',
            'nama_barang' => 'required|string|max:255',
            'harga_standar' => 'required|numeric|min:0',
        ]);

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy(MBarang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
