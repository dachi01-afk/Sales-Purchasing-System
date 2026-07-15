<?php

namespace App\Http\Controllers;

use App\Models\TPo;
use App\Models\TPenerimaan;
use App\Models\TInvoicePurchasing;
use App\Models\TReturPurchasing;
use App\Models\TSo;
use App\Models\TDo;
use App\Models\TInvoiceSales;
use App\Models\TReturSales;
use App\Models\TKwitansi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function pembelian(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $pos = TPo::with('vendor', 'details')
            ->when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->latest()
            ->paginate(10);

        $totalPO = TPo::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->count();

        $totalPenerimaan = TPenerimaan::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->count();

        $totalInvoice = TInvoicePurchasing::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->sum('total');

        return view('laporan.pembelian', compact('pos', 'totalPO', 'totalPenerimaan', 'totalInvoice', 'start', 'end'));
    }

    public function penjualan(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $sos = TSo::with('customer', 'details')
            ->when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->latest()
            ->paginate(10);

        $totalSO = TSo::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->count();

        $totalDO = TDo::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->count();

        $totalInvoice = TInvoiceSales::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->sum('total');

        $totalKwitansi = TKwitansi::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->sum('jumlah');

        return view('laporan.penjualan', compact('sos', 'totalSO', 'totalDO', 'totalInvoice', 'totalKwitansi', 'start', 'end'));
    }

    public function keuangan(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $totalPembelian = TInvoicePurchasing::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->sum('total');

        $totalPenjualan = TInvoiceSales::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->sum('total');

        $totalReturPembelian = TReturPurchasing::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->count();

        $totalReturPenjualan = TReturSales::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->count();

        $totalPenerimaanKwitansi = TKwitansi::when($start, fn($q) => $q->whereDate('tanggal', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('tanggal', '<=', $end))
            ->sum('jumlah');

        return view('laporan.keuangan', compact(
            'totalPembelian', 'totalPenjualan',
            'totalReturPembelian', 'totalReturPenjualan',
            'totalPenerimaanKwitansi', 'start', 'end'
        ));
    }
}
