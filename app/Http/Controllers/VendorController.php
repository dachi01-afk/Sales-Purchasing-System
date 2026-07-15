<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MVendor;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = MVendor::paginate(10);
        return view('master.vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('master.vendor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        MVendor::create($validated);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan');
    }

    public function edit(MVendor $vendor)
    {
        return view('master.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, MVendor $vendor)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $vendor->update($validated);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diupdate');
    }

    public function destroy(MVendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus');
    }
}
