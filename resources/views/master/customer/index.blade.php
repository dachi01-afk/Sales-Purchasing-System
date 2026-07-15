<x-app-layout>
    <x-slot name="header">Daftar Customer</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Customer</h3>
            @can('customer.create')
            <a href="{{ route('customer.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                + Tambah Customer
            </a>
            @endcan
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">NO</th>
                        <th class="px-6 py-3">Nama Customer</th>
                        <th class="px-6 py-3">No. Telp</th>
                        <th class="px-6 py-3">Alamat</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $customer->id_customer }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $customer->nama_customer }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $customer->no_telp ?? '-' }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $customer->alamat ?? '-' }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            @can('customer.edit')
                            <a href="{{ route('customer.edit', $customer) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">
                                Edit
                            </a>
                            @endcan
                            @can('customer.delete')
                            <x-delete-modal
                                :route="route('customer.destroy', $customer)"
                                label="customer {{ $customer->nama_customer }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">Belum ada data customer</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</x-app-layout>