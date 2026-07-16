<x-app-layout>
    <x-slot name="header">DO Detail #{{ $deliveryOrder->id }}</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <dl class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#DO</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $deliveryOrder->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#SO</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $deliveryOrder->salesOrder->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Customer</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $deliveryOrder->salesOrder->customer->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Date</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $deliveryOrder->date->format('d/m/Y') }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Shipping Address</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $deliveryOrder->shipping_address ?? '-' }}</dd>
                </div>
            </dl>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-6">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveryOrder->items as $item)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3 dark:text-gray-300">{{ $item->sku }} — {{ $item->product->name ?? '-' }}</td>
                        <td class="px-4 py-3 dark:text-gray-300">{{ $item->qty }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('delivery-orders.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Back</a>
        </div>
    </div>
</x-app-layout>
