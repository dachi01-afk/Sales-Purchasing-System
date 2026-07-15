<x-app-layout>
    <x-slot name="header">Kwitansi</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Kwitansi</h3>
            @can('kwitansi.create')
            <a href="{{ route('kwitansi.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">+ Kwitansi Baru</a>
            @endcan
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">#Kwitansi</th>
                        <th class="px-6 py-3">#Invoice</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Jumlah</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kwitansis as $k)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $k->id_kwitansi }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">#{{ $k->invoiceSales->id_invoice_sales }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $k->invoiceSales->do->so->customer->nama_customer }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $k->tanggal->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">Rp {{ number_format($k->jumlah, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('kwitansi.show', $k) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5">Detail</a>
                            @can('kwitansi.edit')
                            <a href="{{ route('kwitansi.edit', $k) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">Edit</a>
                            @endcan
                            @can('kwitansi.delete')
                            <x-delete-modal :route="route('kwitansi.destroy', $k)" label="kwitansi #{{ $k->id_kwitansi }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">Belum ada kwitansi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kwitansis->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $kwitansis->links() }}</div>
        @endif
    </div>
</x-app-layout>