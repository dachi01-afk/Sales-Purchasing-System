<x-app-layout>
    <x-slot name="header">Edit Purchase Order</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('po.update', $po) }}" method="POST" x-data="{
                permintaanId: {{ $po->id_permintaan }},
                items: {{ $po->details->map(fn($d) => ['sku' => $d->sku, 'qty' => $d->qty, 'harga' => $d->harga])->toJson() }}
            }">
                @csrf @method('PUT')

                <div class="grid grid-cols-3 gap-4 mb-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Permintaan</label>
                        <select name="id_permintaan" x-model="permintaanId" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Pilih Permintaan</option>
                            @foreach($permintaans as $pr)
                            <option value="{{ $pr->id_permintaan }}" @selected($po->id_permintaan == $pr->id_permintaan)>#{{ $pr->id_permintaan }} — {{ $pr->tanggal->format('d/m/Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Vendor</label>
                        <select name="id_vendor" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Pilih Vendor</option>
                            @foreach($vendors as $v)
                            <option value="{{ $v->id_vendor }}" @selected($po->id_vendor == $v->id_vendor)>{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $po->tanggal->format('Y-m-d')) }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-900 dark:text-white">Item Barang</label>
                        <button type="button" @click="items.push({ sku: '', qty: 1, harga: 0 })" class="text-sm text-blue-600 hover:text-blue-700 font-medium">+ Tambah Item</button>
                    </div>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-3 items-start mb-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
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
                            <div class="w-28 pt-2.5 text-sm text-gray-500 dark:text-gray-400" x-text="'Rp ' + (item.qty * item.harga).toLocaleString()"></div>
                            <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="text-red-600 hover:text-red-700 p-2 mt-1">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Update</button>
                    <a href="{{ route('po.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>