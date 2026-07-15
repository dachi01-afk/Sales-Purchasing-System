<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Welcome --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sistem Sales & Purchasing Management — periode {{ now()->format('F Y') }}</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        @can('po.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">PO Bulan Ini</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPoBulanIni }}</p>
        </div>
        @endcan

        @can('so.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">SO Bulan Ini</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalSoBulanIni }}</p>
        </div>
        @endcan

        @can('invoice_purchasing.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pembelian (Rp)</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
        </div>
        @endcan

        @can('invoice_sales.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penjualan (Rp)</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>
        @endcan
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        @can('po.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">PO Terbaru</h3>
                <a href="{{ route('po.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3">#PO</th>
                            <th class="px-4 py-3">Vendor</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($poTerbaru as $po)
                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">#{{ $po->id_po }}</td>
                            <td class="px-4 py-3 dark:text-gray-300">{{ $po->vendor->nama_vendor }}</td>
                            <td class="px-4 py-3 dark:text-gray-300">{{ $po->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded {{ $po->status == 'selesai' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' }}">{{ $po->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada PO</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endcan

        @can('so.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">SO Terbaru</h3>
                <a href="{{ route('so.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3">#SO</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($soTerbaru as $so)
                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">#{{ $so->id_so }}</td>
                            <td class="px-4 py-3 dark:text-gray-300">{{ $so->customer->nama_customer }}</td>
                            <td class="px-4 py-3 dark:text-gray-300">{{ $so->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded {{ $so->status == 'selesai' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' }}">{{ $so->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada SO</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endcan
    </div>

    {{-- Ringkasan --}}
    @canany(['po.view', 'so.view', 'invoice_sales.view'])
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Ringkasan</h3>
        <div class="flex flex-wrap gap-4">
            @can('po.view')
            <div class="flex items-center gap-2 text-sm">
                <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                <span class="text-gray-600 dark:text-gray-400">PO Pending:</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $poPending }}</span>
            </div>
            @endcan
            @can('so.view')
            <div class="flex items-center gap-2 text-sm">
                <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                <span class="text-gray-600 dark:text-gray-400">SO Pending:</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $soPending }}</span>
            </div>
            @endcan
            @can('invoice_sales.view')
            <div class="flex items-center gap-2 text-sm">
                <span class="w-2 h-2 rounded-full bg-red-400"></span>
                <span class="text-gray-600 dark:text-gray-400">Invoice Sales Belum Lunas:</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $invoiceSalesBelumLunas }}</span>
            </div>
            @endcan
            @can('kwitansi.view')
            <div class="flex items-center gap-2 text-sm">
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                <span class="text-gray-600 dark:text-gray-400">Kwitansi Bulan Ini:</span>
                <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($totalKwitansi, 0, ',', '.') }}</span>
            </div>
            @endcan
        </div>
    </div>
    @endcanany
</x-app-layout>
