<x-app-layout>
    <x-slot name="header">Tambah Penerimaan Barang</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('penerimaan.store') }}" method="POST" x-data="{
                poId: '',
                items: [],
                loadItems() {
                    const select = document.getElementById('id_po');
                    const data = select.options[select.selectedIndex]?.dataset.items;
                    this.items = data ? JSON.parse(data) : [];
                }
            }">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Purchase Order</label>
                        <select name="id_po" id="id_po" x-model="poId" @change="loadItems" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Pilih PO</option>
                            @foreach($pos as $po)
                            <option value="{{ $po->id_po }}" data-items='{{ $po->details->map(fn($d) => ['sku' => $d->sku, 'nama_barang' => $d->barang->nama_barang ?? '', 'qty_po' => $d->qty, 'qty_diterima' => $d->qty]) }}'>#{{ $po->id_po }} — {{ $po->vendor->nama_vendor }} ({{ $po->tanggal->format('d/m/Y') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('keterangan') }}</textarea>
                </div>

                <div class="mb-5">
                    <label class="text-sm font-medium text-gray-900 dark:text-white">Item Diterima</label>
                    <template x-if="items.length === 0">
                        <p class="text-sm text-gray-400 mt-2">Pilih PO terlebih dahulu</p>
                    </template>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-3 items-center mb-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-1">
                                <input type="hidden" :name="'items[' + index + '][sku]'" x-model="item.sku">
                                <span class="text-sm text-gray-900 dark:text-white" x-text="item.sku + ' — ' + item.nama_barang"></span>
                                <span class="text-xs text-gray-400 ml-2">(PO: <span x-text="item.qty_po"></span>)</span>
                            </div>
                            <div class="w-24">
                                <input type="number" :name="'items[' + index + '][qty_diterima]'" x-model="item.qty_diterima" min="0" :max="item.qty_po" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                        </div>
                    </template>
                    @error('items') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                    <a href="{{ route('penerimaan.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>