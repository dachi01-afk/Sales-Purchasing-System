<x-app-layout>
    <x-slot name="header">Receipt Detail #{{ $receipt->id }}</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <dl class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#Receipt</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $receipt->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#Invoice</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $receipt->salesInvoice->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Customer</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $receipt->salesInvoice->deliveryOrder->salesOrder->customer->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Date</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $receipt->date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Amount</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($receipt->amount, 0, ',', '.') }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Notes</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $receipt->notes ?? '-' }}</dd>
                </div>
            </dl>

            <a href="{{ route('receipts.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Back</a>
        </div>
    </div>
</x-app-layout>
