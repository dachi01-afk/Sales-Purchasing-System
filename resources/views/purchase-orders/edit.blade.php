<x-app-layout>
    <x-slot name="header">Edit Purchase Order</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST" x-data="{
                purchaseRequestId: {{ $purchaseOrder->purchase_request_id }},
                items: {{ $purchaseOrder->items->map(fn($d) => ['sku' => $d->sku, 'qty' => $d->qty, 'price' => $d->price])->toJson() }}
            }">
                @csrf @method('PUT')

                <div class="grid grid-cols-3 gap-4 mb-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Purchase Request</label>
                        <select name="purchase_request_id" x-model="purchaseRequestId" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Select Purchase Request</option>
                            @foreach($purchaseRequests as $pr)
                            <option value="{{ $pr->id }}" @selected($purchaseOrder->purchase_request_id == $pr->id)>#{{ $pr->id }} — {{ $pr->date->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Vendor</label>
                        <select name="vendor_id" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $v)
                            <option value="{{ $v->id }}" @selected($purchaseOrder->vendor_id == $v->id)>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                        <input type="date" name="date" value="{{ old('date', $purchaseOrder->date->format('Y-m-d')) }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-900 dark:text-white">Items</label>
                        <button type="button" @click="items.push({ sku: '', qty: 1, price: 0 })" class="text-sm text-blue-600 hover:text-blue-700 font-medium">+ Add Item</button>
                    </div>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-3 items-start mb-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-1">
                                <select :name="'items[' + index + '][sku]'" x-model="item.sku" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $p)
                                    <option value="{{ $p->sku }}">{{ $p->sku }} — {{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-20">
                                <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty" min="1" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div class="w-28">
                                <input type="number" step="0.01" :name="'items[' + index + '][price]'" x-model="item.price" min="0" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div class="w-28 pt-2.5 text-sm text-gray-500 dark:text-gray-400" x-text="'Rp ' + (item.qty * item.price).toLocaleString('id-ID')"></div>
                            <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="text-red-600 hover:text-red-700 p-2 mt-1">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Update</button>
                    <a href="{{ route('purchase-orders.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
