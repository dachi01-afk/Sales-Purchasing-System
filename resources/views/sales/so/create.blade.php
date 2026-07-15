<x-app-layout>
    <x-slot name="header">Tambah Sales Order</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('so.store') }}" method="POST" x-data="{
                items: [],
                get total() { return this.items.reduce((sum, i) => sum + (Number(i.qty) * Number(i.harga)), 0) },
                addItem() { this.items.push({ sku: '', nama_barang: '', qty: 1, harga: 0 }) },
                removeItem(i) { this.items.splice(i, 1) }
            }">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                        <select name="id_customer" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Pilih Customer</option>
                            @foreach($customers as $c)
                            <option value="{{ $c->id_customer }}">{{ $c->nama_customer }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select name="status" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="draft">Draft</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-900 dark:text-white">Item</label>
                        <button type="button" @click="addItem" class="text-sm text-blue-600 hover:text-blue-700">+ Tambah Item</button>
                    </div>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-3 items-center mb-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-1">
                                <select :name="'items[' + index + '][sku]'" x-model="item.sku" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="">Pilih Barang</option>
                                    @foreach($barangs as $b)
                                    <option value="{{ $b->sku }}">{{ $b->sku }} — {{ $b->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-20">
                                <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty" min="1" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div class="w-28">
                                <input type="number" step="0.01" :name="'items[' + index + '][harga]'" x-model="item.harga" min="0" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div class="w-24 text-sm text-gray-900 dark:text-white text-right" x-text="'Rp ' + (Number(item.qty) * Number(item.harga)).toLocaleString('id-ID')"></div>
                            <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700">&times;</button>
                        </div>
                    </template>
                    <template x-if="items.length === 0">
                        <p class="text-sm text-gray-400">Klik "+ Tambah Item" untuk mulai</p>
                    </template>
                </div>

                <div class="text-right text-lg font-semibold text-gray-900 dark:text-white mb-5">
                    Total: <span x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                    <a href="{{ route('so.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>