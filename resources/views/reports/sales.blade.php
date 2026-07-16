<x-app-layout>
    <x-slot name="header">Sales Report</x-slot>

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
                <a href="{{ route('reports.sales') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 font-medium rounded-lg text-sm px-4 py-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total SO</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSO }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total DO</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDO }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Invoice (Rp)</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalInvoice, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Receipts (Rp)</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalReceipts, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">#SO</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Items</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesOrders as $so)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $so->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $so->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $so->customer->name }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $so->items->count() }} item(s)</td>
                        <td class="px-6 py-4">{{ $so->status }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($salesOrders->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $salesOrders->links() }}</div>
        @endif
    </div>
</x-app-layout>
