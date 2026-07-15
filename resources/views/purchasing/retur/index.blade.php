<x-app-layout>
    <x-slot name="header">Retur Purchasing</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Retur</h3>
            @can('retur_purchasing.create')
            <a href="{{ route('retur-purchasing.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                + Retur Baru
            </a>
            @endcan
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">#Retur</th>
                        <th class="px-6 py-3">#Terima</th>
                        <th class="px-6 py-3">Vendor</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Item</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returs as $r)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ $r->id_retur_purchasing }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">#{{ $r->penerimaan->id_penerimaan }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $r->penerimaan->po->vendor->nama_vendor }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $r->tanggal->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $r->details->count() }} item</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('retur-purchasing.show', $r) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5">Detail</a>
                            @can('retur_purchasing.edit')
                            <a href="{{ route('retur-purchasing.edit', $r) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">Edit</a>
                            @endcan
                            @can('retur_purchasing.delete')
                            <x-delete-modal :route="route('retur-purchasing.destroy', $r)" label="retur #{{ $r->id_retur_purchasing }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">Belum ada retur</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($returs->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $returs->links() }}</div>
        @endif
    </div>
</x-app-layout>