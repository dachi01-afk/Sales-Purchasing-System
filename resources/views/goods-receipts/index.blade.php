<x-app-layout>
    <x-slot name="header">Goods Receipts</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Goods Receipts</h3>
            @can('goods_receipts.create')
            <a href="{{ route('goods-receipts.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                + New Goods Receipt
            </a>
            @endcan
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">#Receipt</th>
                        <th class="px-6 py-3">#PO</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Items</th>
                        <th class="px-6 py-3">Notes</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($goodsReceipts as $gr)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $gr->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">#{{ $gr->purchaseOrder->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $gr->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $gr->items->count() }} items</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $gr->notes ?? '-' }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            @can('goods_receipts.edit')
                            <a href="{{ route('goods-receipts.edit', $gr) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">Edit</a>
                            @endcan
                            @can('goods_receipts.delete')
                            <x-delete-modal :route="route('goods-receipts.destroy', $gr)" label="goods receipt #{{ $gr->id }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No goods receipts yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($goodsReceipts->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $goodsReceipts->links() }}</div>
        @endif
    </div>
</x-app-layout>
