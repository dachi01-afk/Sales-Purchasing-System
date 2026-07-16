<x-app-layout>
    <x-slot name="header">Return Detail #{{ $purchaseReturn->id }}</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <dl class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#Return</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $purchaseReturn->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#Receiving</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $purchaseReturn->goodsReceipt->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Vendor</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $purchaseReturn->goodsReceipt->purchaseOrder->vendor->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Date</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $purchaseReturn->date->format('d/m/Y') }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Reason</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $purchaseReturn->reason ?? '-' }}</dd>
                </div>
            </dl>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-6">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">Return Qty</th>
                        <th class="px-4 py-3">Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseReturn->items as $d)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->sku }} — {{ $d->product->name ?? '-' }}</td>
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->qty }}</td>
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->reason ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('purchase-returns.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Back</a>
        </div>
    </div>
</x-app-layout>
