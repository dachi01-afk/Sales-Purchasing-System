<x-app-layout>
    <x-slot name="header">Detail Retur #{{ $returPurchasing->id_retur_purchasing }}</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <dl class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#Retur</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $returPurchasing->id_retur_purchasing }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#Penerimaan</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $returPurchasing->penerimaan->id_penerimaan }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Vendor</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $returPurchasing->penerimaan->po->vendor->nama_vendor }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Tanggal</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $returPurchasing->tanggal->format('d/m/Y') }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Alasan</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $returPurchasing->alasan ?? '-' }}</dd>
                </div>
            </dl>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-6">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">Barang</th>
                        <th class="px-4 py-3">Qty Retur</th>
                        <th class="px-4 py-3">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returPurchasing->details as $d)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->sku }} — {{ $d->barang->nama_barang ?? '-' }}</td>
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->qty_retur }}</td>
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->alasan_item ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('retur-purchasing.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Kembali</a>
        </div>
    </div>
</x-app-layout>