<x-app-layout>
    <x-slot name="header">Sales Returns</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Return Data</h3>
            @can('sales_returns.create')
            <a href="{{ route('sales-returns.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">+ New Return</a>
            @endcan
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">#Return</th>
                        <th class="px-6 py-3">#DO</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Items</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $r)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $r->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">#{{ $r->deliveryOrder->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $r->deliveryOrder->salesOrder->customer->name }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $r->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $r->items->count() }} item(s)</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('sales-returns.show', $r) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5">Detail</a>
                            @can('sales_returns.edit')
                            <a href="{{ route('sales-returns.edit', $r) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">Edit</a>
                            @endcan
                            @can('sales_returns.delete')
                            <x-delete-modal :route="route('sales-returns.destroy', $r)" label="return #{{ $r->id }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No returns yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($returns->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $returns->links() }}</div>
        @endif
    </div>
</x-app-layout>
