<x-app-layout>
    <x-slot name="header">Financial Report</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-4">
        <div class="p-4">
            <form method="GET" class="flex gap-4 items-end">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">From Date</label>
                    <input type="date" name="start" value="{{ $start ?? '' }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-2">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">To Date</label>
                    <input type="date" name="end" value="{{ $end ?? '' }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-2">
                </div>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">Filter</button>
                <a href="{{ route('reports.financial') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 font-medium rounded-lg text-sm px-4 py-2">Reset</a>
            </form>
        </div>
    </div>

    @php
        $grossProfit = $totalPenjualan - $totalPembelian;
        $totalInflow = $totalPenerimaanKwitansi;
        $balance = $totalInflow - $totalPembelian;
    @endphp

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Purchases (Invoice)</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Sales (Invoice)</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Purchase Returns</p>
            <p class="text-2xl font-bold text-orange-600">{{ $totalReturPembelian }} time(s)</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Sales Returns</p>
            <p class="text-2xl font-bold text-orange-600">{{ $totalReturPenjualan }} time(s)</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Cash Receipts</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalInflow, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Gross Profit / Loss</p>
            <p class="text-2xl font-bold {{ $grossProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format(abs($grossProfit), 0, ',', '.') }}
                <span class="text-sm">({{ $grossProfit >= 0 ? 'Profit' : 'Loss' }})</span>
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Cash Flow Summary</h4>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <tbody>
                <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-3 text-gray-900 dark:text-white">Cash Inflow (Receipts)</td>
                    <td class="px-4 py-3 text-right text-green-600 font-medium">Rp {{ number_format($totalInflow, 0, ',', '.') }}</td>
                </tr>
                <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-3 text-gray-900 dark:text-white">Cash Outflow (Purchases)</td>
                    <td class="px-4 py-3 text-right text-red-600 font-medium">(Rp {{ number_format($totalPembelian, 0, ',', '.') }})</td>
                </tr>
                <tr class="font-bold">
                    <td class="px-4 py-3 text-gray-900 dark:text-white">Cash Balance</td>
                    <td class="px-4 py-3 text-right {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($balance), 0, ',', '.') }}
                        ({{ $balance >= 0 ? 'Surplus' : 'Deficit' }})
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>
