<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MCustomer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = MCustomer::paginate(10);
        return view('master.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('master.customer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        MCustomer::create($validated);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan');
    }

    public function edit(MCustomer $customer)
    {
        return view('master.customer.edit', compact('customer'));
    }

    public function update(Request $request, MCustomer $customer)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil diupdate');
    }

    public function destroy(MCustomer $customer)
    {
        $customer->delete();
        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus');
    }
}
