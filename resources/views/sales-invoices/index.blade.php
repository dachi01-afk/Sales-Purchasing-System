<x-app-layout>
    <x-slot name="header">Sales Invoices</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Invoice Data</h3>
            @can('sales_invoices.create')
            <a href="{{ route('sales-invoices.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">+ New Invoice</a>
            @endcan
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">#Invoice</th>
                        <th class="px-6 py-3">#DO</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Payment Link</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesInvoices as $inv)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $inv->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">#{{ $inv->deliveryOrder->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $inv->deliveryOrder->salesOrder->customer->name }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $inv->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">$ {{ number_format($inv->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $inv->status === 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : '' }}
                                {{ $inv->status === 'draft' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                                {{ $inv->status === 'pending_payment' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                {{ $inv->status === 'expired' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : '' }}
                                {{ $inv->status === 'cancelled' ? 'bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-300' : '' }}">{{ $inv->status }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($inv->xendit_invoice_url)
                            <a href="{{ $inv->xendit_invoice_url }}" target="_blank" class="text-blue-600 hover:underline text-xs">Open Link</a>
                            @else
                            <span class="text-gray-400">--</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('sales-invoices.show', $inv) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5">Detail</a>
                            @can('sales_invoices.edit')
                            @if($inv->status === 'draft')
                            <form action="{{ route('sales-invoices.send-payment-link', $inv) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-1.5">Send Link</button>
                            </form>
                            @endif
                            <a href="{{ route('sales-invoices.edit', $inv) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">Edit</a>
                            @endcan
                            @can('sales_invoices.delete')
                            <x-delete-modal :route="route('sales-invoices.destroy', $inv)" label="invoice #{{ $inv->id }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No invoices yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($salesInvoices->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $salesInvoices->links() }}</div>
        @endif
    </div>
</x-app-layout>
