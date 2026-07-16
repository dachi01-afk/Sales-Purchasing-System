<x-app-layout>
    <x-slot name="header">Purchase Requests</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Purchase Requests</h3>
            @can('purchase_requests.create')
            <a href="{{ route('purchase-requests.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                + New Purchase Request
            </a>
            @endcan
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">NO</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Items</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Created By</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseRequests as $pr)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $pr->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $pr->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $pr->details->count() }} items</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2 py-0.5 rounded @if($pr->status == 'draft') bg-yellow-100 text-yellow-800 @elseif($pr->status == 'approved') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($pr->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $pr->creator->name }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            @can('purchase_requests.edit')
                            <a href="{{ route('purchase-requests.edit', $pr) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">
                                Edit
                            </a>
                            @endcan
                            @can('purchase_requests.delete')
                            <x-delete-modal
                                :route="route('purchase-requests.destroy', $pr)"
                                label="purchase request #{{ $pr->id }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No purchase requests yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchaseRequests->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $purchaseRequests->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
