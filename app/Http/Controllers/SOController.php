<?php

namespace App\Http\Controllers;

use App\Models\TSo;
use App\Models\MCustomer;
use App\Models\MBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SOController extends Controller
{
    public function index()
    {
        $sos = TSo::with('details.barang', 'customer', 'creator')->latest()->paginate(10);
        return view('sales.so.index', compact('sos'));
    }

    public function create()
    {
        $customers = MCustomer::all();
        $barangs = MBarang::all();
        return view('sales.so.create', compact('customers', 'barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_customer' => 'required|exists:m_customer,id_customer',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,dikirim,selesai',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['harga']);

        $so = TSo::create([
            'id_customer' => $validated['id_customer'],
            'tanggal' => $validated['tanggal'],
            'total' => $total,
            'status' => $validated['status'],
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $so->details()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);
        }

        return redirect()->route('so.index')->with('success', 'Sales Order berhasil dibuat');
    }

    public function show(TSo $so)
    {
        $so->load('details.barang', 'customer', 'creator');
        return view('sales.so.show', compact('so'));
    }

    public function edit(TSo $so)
    {
        $so->load('details');
        $customers = MCustomer::all();
        $barangs = MBarang::all();
        return view('sales.so.edit', compact('so', 'customers', 'barangs'));
    }

    public function update(Request $request, TSo $so)
    {
        $validated = $request->validate([
            'id_customer' => 'required|exists:m_customer,id_customer',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,dikirim,selesai',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:m_barang,sku',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $total = collect($validated['items'])->sum(fn($i) => $i['qty'] * $i['harga']);

        $so->update([
            'id_customer' => $validated['id_customer'],
            'tanggal' => $validated['tanggal'],
            'total' => $total,
            'status' => $validated['status'],
        ]);

        $so->details()->delete();
        foreach ($validated['items'] as $item) {
            $so->details()->create([
                'sku' => $item['sku'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);
        }

        return redirect()->route('so.index')->with('success', 'Sales Order berhasil diupdate');
    }

    public function destroy(TSo $so)
    {
        $so->delete();
        return redirect()->route('so.index')->with('success', 'Sales Order berhasil dihapus');
    }
}