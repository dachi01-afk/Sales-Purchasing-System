<x-app-layout>
    <x-slot name="header">Tambah Delivery Order</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('do.store') }}" method="POST" x-data="{
                soId: '',
                items: [],
                loadItems() {
                    const select = document.getElementById('id_so');
                    const data = select.options[select.selectedIndex]?.dataset.items;
                    this.items = data ? JSON.parse(data).map(i => ({ ...i, qty_dikirim: i.qty })) : [];
                }
            }">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sales Order</label>
                        <select name="id_so" id="id_so" x-model="soId" @change="loadItems" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Pilih SO</option>
                            @foreach($sos as $so)
                            <option value="{{ $so->id_so }}" data-items='{{ $so->details->map(fn($d) => ['sku' => $d->sku, 'nama_barang' => $d->barang->nama_barang ?? '', 'qty' => $d->qty]) }}'>#{{ $so->id_so }} — {{ $so->customer->nama_customer }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Pengiriman</label>
                    <textarea name="alamat_pengiriman" rows="2" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('alamat_pengiriman') }}</textarea>
                </div>

                <div class="mb-5">
                    <label class="text-sm font-medium text-gray-900 dark:text-white">Item Dikirim</label>
                    <template x-if="items.length === 0">
                        <p class="text-sm text-gray-400 mt-2">Pilih SO terlebih dahulu</p>
                    </template>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-3 items-center mb-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-1">
                                <input type="hidden" :name="'items[' + index + '][sku]'" x-model="item.sku">
                                <span class="text-sm text-gray-900 dark:text-white" x-text="item.sku + ' — ' + item.nama_barang"></span>
                                <span class="text-xs text-gray-400 ml-2">(SO: <span x-text="item.qty"></span>)</span>
                            </div>
                            <div class="w-24">
                                <input type="number" :name="'items[' + index + '][qty_dikirim]'" x-model="item.qty_dikirim" min="0" :max="item.qty" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                    <a href="{{ route('do.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>