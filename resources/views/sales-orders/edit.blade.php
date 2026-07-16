<x-app-layout>
    <x-slot name="header">Edit SO #{{ $salesOrder->id }}</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('sales-orders.update', $salesOrder) }}" method="POST" x-data="{
                items: {{ $salesOrder->items->map(fn($d) => ['sku' => $d->sku, 'name' => $d->product->name ?? '', 'qty' => $d->qty, 'price' => $d->price])->toJson() }},
                get total() { return this.items.reduce((sum, i) => sum + (Number(i.qty) * Number(i.price)), 0) },
                addItem() { this.items.push({ sku: '', name: '', qty: 1, price: 0 }) },
                removeItem(i) { this.items.splice(i, 1) }
            }">
                @csrf @method('PUT')

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                        <select name="customer_id" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            @foreach($customers as $c)
                            <option value="{{ $c->id }}" @selected($salesOrder->customer_id == $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                        <input type="date" name="date" value="{{ old('date', $salesOrder->date->format('Y-m-d')) }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select name="status" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="draft" @selected($salesOrder->status == 'draft')>Draft</option>
                        <option value="sent" @selected($salesOrder->status == 'sent')>Sent</option>
                        <option value="completed" @selected($salesOrder->status == 'completed')>Completed</option>
                    </select>
                </div>

                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-900 dark:text-white">Items</label>
                        <button type="button" @click="addItem" class="text-sm text-blue-600 hover:text-blue-700">+ Add Item</button>
                    </div>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-3 items-center mb-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-1">
                                <select :name="'items[' + index + '][sku]'" x-model="item.sku" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    @foreach($products as $product)
                                    <option value="{{ $product->sku }}" x-bind:selected="item.sku == '{{ $product->sku }}'">{{ $product->sku }} — {{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-20">
                                <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty" min="1" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div class="w-28">
                                <input type="number" step="0.01" :name="'items[' + index + '][price]'" x-model="item.price" min="0" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div class="w-24 text-sm text-gray-900 dark:text-white text-right" x-text="'Rp ' + (Number(item.qty) * Number(item.price)).toLocaleString('id-ID')"></div>
                            <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700">&times;</button>
                        </div>
                    </template>
                </div>

                <div class="text-right text-lg font-semibold text-gray-900 dark:text-white mb-5">
                    Total: <span x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Update</button>
                    <a href="{{ route('sales-orders.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
