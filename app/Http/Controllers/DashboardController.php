<?php

namespace App\Http\Controllers;

use App\Models\MBarang;
use App\Models\MVendor;
use App\Models\MCustomer;
use App\Models\TPo;
use App\Models\TSo;
use App\Models\TDo;
use App\Models\TInvoiceSales;
use App\Models\TInvoicePurchasing;
use App\Models\TKwitansi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = now();
        $bulanIni = [$now->startOfMonth()->format('Y-m-d'), $now->copy()->endOfMonth()->format('Y-m-d')];

        $totalBarang = MBarang::count();
        $totalVendor = MVendor::count();
        $totalCustomer = MCustomer::count();

        $totalPoBulanIni = TPo::whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->count();
        $totalSoBulanIni = TSo::whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->count();

        $totalPembelian = TInvoicePurchasing::whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->sum('total');
        $totalPenjualan = TInvoiceSales::whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->sum('total');
        $totalKwitansi = TKwitansi::whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->sum('jumlah');

        $poTerbaru = TPo::with('vendor')->latest()->take(5)->get();
        $soTerbaru = TSo::with('customer')->latest()->take(5)->get();

        $poPending = TPo::whereNotIn('status', ['selesai', 'dibatalkan'])->count();
        $soPending = TSo::whereNotIn('status', ['selesai', 'dibatalkan'])->count();
        $invoiceSalesBelumLunas = TInvoiceSales::where('status', 'draft')->count();

        return view('dashboard', compact(
            'totalBarang', 'totalVendor', 'totalCustomer',
            'totalPoBulanIni', 'totalSoBulanIni',
            'totalPembelian', 'totalPenjualan', 'totalKwitansi',
            'poTerbaru', 'soTerbaru',
            'poPending', 'soPending', 'invoiceSalesBelumLunas'
        ));
    }
}
