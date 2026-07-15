<x-app-layout>
    <x-slot name="header">Laporan Keuangan</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-4">
        <div class="p-4">
            <form method="GET" class="flex gap-4 items-end">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Dari Tanggal</label>
                    <input type="date" name="start" value="{{ $start ?? '' }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-2">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Sampai Tanggal</label>
                    <input type="date" name="end" value="{{ $end ?? '' }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-2">
                </div>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">Filter</button>
                <a href="{{ route('laporan.keuangan') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 font-medium rounded-lg text-sm px-4 py-2">Reset</a>
            </form>
        </div>
    </div>

    @php
        $labaKotor = $totalPenjualan - $totalPembelian;
        $totalMasuk = $totalPenerimaanKwitansi;
        $saldo = $totalMasuk - $totalPembelian;
    @endphp

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Pembelian (Invoice)</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Penjualan (Invoice)</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Retur Pembelian</p>
            <p class="text-2xl font-bold text-orange-600">{{ $totalReturPembelian }} kali</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Retur Penjualan</p>
            <p class="text-2xl font-bold text-orange-600">{{ $totalReturPenjualan }} kali</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Penerimaan Kas (Kwitansi)</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Laba / Rugi Kotor</p>
            <p class="text-2xl font-bold {{ $labaKotor >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format(abs($labaKotor), 0, ',', '.') }}
                <span class="text-sm">({{ $labaKotor >= 0 ? 'Laba' : 'Rugi' }})</span>
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Arus Kas</h4>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <tbody>
                <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-3 text-gray-900 dark:text-white">Penerimaan Kas (Kwitansi)</td>
                    <td class="px-4 py-3 text-right text-green-600 font-medium">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                </tr>
                <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-3 text-gray-900 dark:text-white">Pengeluaran (Pembelian)</td>
                    <td class="px-4 py-3 text-right text-red-600 font-medium">(Rp {{ number_format($totalPembelian, 0, ',', '.') }})</td>
                </tr>
                <tr class="font-bold">
                    <td class="px-4 py-3 text-gray-900 dark:text-white">Saldo Kas</td>
                    <td class="px-4 py-3 text-right {{ $saldo >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($saldo), 0, ',', '.') }}
                        ({{ $saldo >= 0 ? 'Surplus' : 'Defisit' }})
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>
