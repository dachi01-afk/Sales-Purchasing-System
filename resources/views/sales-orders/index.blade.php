<x-app-layout>
    <x-slot name="header">Sales Order</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Order Data</h3>
            @can('sales_orders.create')
            <a href="{{ route('sales-orders.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">+ New SO</a>
            @endcan
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">#SO</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesOrders as $salesOrder)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $salesOrder->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $salesOrder->customer->name }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $salesOrder->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">Rp {{ number_format($salesOrder->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $salesOrder->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : ($salesOrder->status === 'sent' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300') }}">{{ $salesOrder->status }}</span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('sales-orders.show', $salesOrder) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5">Detail</a>
                            @can('sales_orders.edit')
                            <a href="{{ route('sales-orders.edit', $salesOrder) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">Edit</a>
                            @endcan
                            @can('sales_orders.delete')
                            <x-delete-modal :route="route('sales-orders.destroy', $salesOrder)" label="SO #{{ $salesOrder->id }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No sales orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($salesOrders->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $salesOrders->links() }}</div>
        @endif
    </div>
</x-app-layout>
