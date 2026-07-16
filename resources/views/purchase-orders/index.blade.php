<x-app-layout>
    <x-slot name="header">Purchase Orders</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Purchase Orders</h3>
            @can('purchase_orders.create')
            <a href="{{ route('purchase-orders.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                + New Purchase Order
            </a>
            @endcan
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">#PO</th>
                        <th class="px-6 py-3">Request</th>
                        <th class="px-6 py-3">Vendor</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Items</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $po->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">#{{ $po->purchaseRequest->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $po->vendor->name }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $po->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $po->details->count() }} items</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2 py-0.5 rounded
                                @if($po->status == 'draft') bg-yellow-100 text-yellow-800
                                @elseif($po->status == 'shipped') bg-blue-100 text-blue-800
                                @elseif($po->status == 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($po->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            @can('purchase_orders.edit')
                            <a href="{{ route('purchase-orders.edit', $po) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">Edit</a>
                            @endcan
                            @can('purchase_orders.delete')
                            <x-delete-modal :route="route('purchase-orders.destroy', $po)" label="purchase order #{{ $po->id }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No purchase orders yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchaseOrders->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $purchaseOrders->links() }}</div>
        @endif
    </div>
</x-app-layout>
