<x-app-layout>
    <x-slot name="header">Add Sales Invoice</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('sales-invoices.store') }}" method="POST" x-data="{
                doId: '',
                items: [],
                get total() { return this.items.reduce((sum, i) => sum + (Number(i.qty) * Number(i.price)), 0) },
                loadItems() {
                    const select = document.getElementById('delivery_order_id');
                    const data = select.options[select.selectedIndex]?.dataset.items;
                    this.items = data ? JSON.parse(data).map(i => ({ ...i, qty: i.qty, price: i.price || 0 })) : [];
                }
            }">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Delivery Order</label>
                        <select name="delivery_order_id" id="delivery_order_id" x-model="doId" @change="loadItems" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Select DO</option>
                            @foreach($deliveryOrders as $do)
                            <option value="{{ $do->id }}" data-items='{{ $do->items->map(fn($d) => ['sku' => $d->sku, 'qty' => $d->qty, 'price' => optional($do->salesOrder->items->firstWhere('sku', $d->sku))->price ?? 0]) }}'>#{{ $do->id }} — {{ $do->salesOrder->customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select name="status" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="draft">Draft</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="text-sm font-medium text-gray-900 dark:text-white">Items</label>
                    <template x-if="items.length === 0">
                        <p class="text-sm text-gray-400 mt-2">Select a DO first</p>
                    </template>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-3 items-center mb-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-[2]">
                                <input type="hidden" :name="'items[' + index + '][sku]'" x-model="item.sku">
                                <span class="text-sm text-gray-900 dark:text-white" x-text="item.sku"></span>
                            </div>
                            <div class="w-20">
                                <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty" min="1" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div class="w-28">
                                <input type="number" step="0.01" :name="'items[' + index + '][price]'" x-model="item.price" min="0" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div class="w-28 text-sm text-gray-900 dark:text-white text-right" x-text="'$ ' + (Number(item.qty) * Number(item.price)).toFixed(2)"></div>
                        </div>
                    </template>
                </div>

                <div class="text-right text-lg font-semibold text-gray-900 dark:text-white mb-5">
                    Total: <span x-text="'$ ' + total.toFixed(2)"></span>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Save</button>
                    <a href="{{ route('sales-invoices.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
