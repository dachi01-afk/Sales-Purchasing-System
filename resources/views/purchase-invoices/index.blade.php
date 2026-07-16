<x-app-layout>
    <x-slot name="header">Purchase Invoice</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Purchase Invoice Data</h3>
            @can('purchase_invoices.create')
            <a href="{{ route('purchase-invoices.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">+ New Invoice</a>
            @endcan
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">#Invoice</th>
                        <th class="px-6 py-3">#PO</th>
                        <th class="px-6 py-3">Vendor</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $inv->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">#{{ $inv->purchaseOrder->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $inv->purchaseOrder->vendor->name }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $inv->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $inv->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $inv->status }}</span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('purchase-invoices.show', $inv) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5">Detail</a>
                            @can('purchase_invoices.edit')
                            <a href="{{ route('purchase-invoices.edit', $inv) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">Edit</a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No purchase invoices yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $invoices->links() }}</div>
        @endif
    </div>
</x-app-layout>
