<x-app-layout>
    <x-slot name="header">Daftar Barang</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Barang</h3>
            @can('barang.create')
            <a href="{{ route('barang.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                + Tambah Barang
            </a>
            @endcan
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">SKU</th>
                        <th class="px-6 py-3">Nama Barang</th>
                        <th class="px-6 py-3">Harga Standar</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $barang->sku }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $barang->nama_barang }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">Rp {{ number_format($barang->harga_standar, 2) }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            @can('barang.edit')
                            <a href="{{ route('barang.edit', $barang->sku) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">
                                Edit
                            </a>
                            @endcan
                            @can('barang.delete')
                            <x-delete-modal
                                :route="route('barang.destroy', $barang->sku)"
                                label="barang {{ $barang->nama_barang }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">Belum ada data barang</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($barangs->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $barangs->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
