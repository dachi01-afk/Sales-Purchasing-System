<x-app-layout>
    <x-slot name="header">Detail Invoice #{{ $invoicePurchasing->id_invoice_purchasing }}</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <dl class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#Invoice</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $invoicePurchasing->id_invoice_purchasing }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">#PO</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $invoicePurchasing->po->id_po }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Vendor</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoicePurchasing->po->vendor->nama_vendor }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Tanggal</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoicePurchasing->tanggal->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Status</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs rounded-full {{ $invoicePurchasing->status === 'lunas' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' }}">
                            {{ $invoicePurchasing->status }}
                        </span>
                    </dd>
                </div>
            </dl>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-6">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">Barang</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoicePurchasing->details as $d)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->sku }} — {{ $d->barang->nama_barang ?? '-' }}</td>
                        <td class="px-4 py-3 dark:text-gray-300">{{ $d->qty }}</td>
                        <td class="px-4 py-3 dark:text-gray-300">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right dark:text-gray-300">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="text-sm font-semibold text-gray-900 dark:text-white">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right">Total</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($invoicePurchasing->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <a href="{{ route('invoice-purchasing.index') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">Kembali</a>
        </div>
    </div>
</x-app-layout>