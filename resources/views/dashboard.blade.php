<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Welcome --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Welcome, {{ Auth::user()->name }}!</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sales & Purchasing Management System — {{ now()->format('F Y') }}</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        @can('purchase_orders.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">PO This Month</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPoThisMonth }}</p>
        </div>
        @endcan

        @can('sales_orders.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">SO This Month</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalSoThisMonth }}</p>
        </div>
        @endcan

        @can('purchase_invoices.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Purchases ($)</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</p>
        </div>
        @endcan

        @can('sales_invoices.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sales ($)</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
        </div>
        @endcan
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        @can('purchase_orders.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Latest POs</h3>
                <a href="{{ route('purchase-orders.index') }}" class="text-xs text-blue-600 hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3">#PO</th>
                            <th class="px-4 py-3">Vendor</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestPOs as $po)
                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">#{{ $po->id }}</td>
                            <td class="px-4 py-3 dark:text-gray-300">{{ $po->vendor->name }}</td>
                            <td class="px-4 py-3 dark:text-gray-300">{{ $po->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded {{ $po->status == 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' }}">{{ $po->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No POs yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endcan

        @can('sales_orders.view')
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Latest SOs</h3>
                <a href="{{ route('sales-orders.index') }}" class="text-xs text-blue-600 hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3">#SO</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestSOs as $so)
                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">#{{ $so->id }}</td>
                            <td class="px-4 py-3 dark:text-gray-300">{{ $so->customer->name }}</td>
                            <td class="px-4 py-3 dark:text-gray-300">{{ $so->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded {{ $so->status == 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' }}">{{ $so->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No SOs yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endcan
    </div>

    {{-- Summary --}}
    @canany(['purchase_orders.view', 'sales_orders.view', 'sales_invoices.view'])
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Summary</h3>
        <div class="flex flex-wrap gap-4">
            @can('purchase_orders.view')
            <div class="flex items-center gap-2 text-sm">
                <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                <span class="text-gray-600 dark:text-gray-400">Pending POs:</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $pendingPOs }}</span>
            </div>
            @endcan
            @can('sales_orders.view')
            <div class="flex items-center gap-2 text-sm">
                <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                <span class="text-gray-600 dark:text-gray-400">Pending SOs:</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $pendingSOs }}</span>
            </div>
            @endcan
            @can('sales_invoices.view')
            <div class="flex items-center gap-2 text-sm">
                <span class="w-2 h-2 rounded-full bg-red-400"></span>
                <span class="text-gray-600 dark:text-gray-400">Unpaid Sales Invoices:</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $unpaidInvoices }}</span>
            </div>
            @endcan
            @can('receipts.view')
            <div class="flex items-center gap-2 text-sm">
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                <span class="text-gray-600 dark:text-gray-400">Receipts This Month:</span>
                <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($totalReceipts, 0, ',', '.') }}</span>
            </div>
            @endcan
        </div>
    </div>
    @endcanany
</x-app-layout>
