<x-app-layout>
    <x-slot name="header">Vendor List</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Vendor Data</h3>
            @can('vendors.create')
            <a href="{{ route('vendors.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                + Add Vendor
            </a>
            @endcan
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Vendor Name</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">Address</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $vendor->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $vendor->name }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $vendor->phone ?? '-' }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $vendor->address ?? '-' }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            @can('vendors.edit')
                            <a href="{{ route('vendors.edit', $vendor) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">
                                Edit
                            </a>
                            @endcan
                            @can('vendors.delete')
                            <x-delete-modal
                                :route="route('vendors.destroy', $vendor)"
                                label="vendor {{ $vendor->name }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No vendor data yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vendors->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $vendors->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
