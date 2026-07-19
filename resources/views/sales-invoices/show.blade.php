<x-app-layout>
    <x-slot name="header">Sales Invoice Detail #{{ $salesInvoice->id }}</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <dl class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#Invoice</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $salesInvoice->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#DO</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $salesInvoice->deliveryOrder->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Customer</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $salesInvoice->deliveryOrder->salesOrder->customer->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Date</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $salesInvoice->date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Status</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $salesInvoice->status === 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : '' }}
                            {{ $salesInvoice->status === 'draft' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : '' }}
                            {{ $salesInvoice->status === 'pending_payment' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : '' }}
                            {{ $salesInvoice->status === 'expired' ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : '' }}
                            {{ $salesInvoice->status === 'cancelled' ? 'bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-300' : '' }}">{{ $salesInvoice->status }}</span>
                    </dd>
                </div>
            </dl>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-6">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesInvoice->items as $d)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->sku }} — {{ $d->product->name ?? '-' }}</td>
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->qty }}</td>
                        <td class="px-4 py-3 dark:text-gray-300">Rp {{ number_format($d->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right dark:text-gray-300">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="text-sm font-semibold text-gray-900 dark:text-white">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right">Total</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($salesInvoice->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="flex gap-2">
                <a href="{{ route('sales-invoices.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Back</a>

                @can('sales_invoices.edit')
                @if($salesInvoice->status === 'draft')
                <form action="{{ route('sales-invoices.send-payment-link', $salesInvoice) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">Send Payment Link</button>
                </form>
                @endif
                @endcan

                @if($salesInvoice->xendit_invoice_url)
                <a href="{{ $salesInvoice->xendit_invoice_url }}" target="_blank" class="inline-flex items-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Open Payment Page</a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
