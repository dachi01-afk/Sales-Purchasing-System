<x-app-layout>
    <x-slot name="header">Customer List</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Data</h3>
            @can('customers.create')
            <a href="{{ route('customers.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                + Add Customer
            </a>
            @endcan
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Customer Name</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">Address</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $customer->id }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $customer->name }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $customer->phone ?? '-' }}</td>
                        <td class="px-6 py-4 dark:text-gray-300">{{ $customer->address ?? '-' }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            @can('customers.edit')
                            <a href="{{ route('customers.edit', $customer) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5">
                                Edit
                            </a>
                            @endcan
                            @can('customers.delete')
                            <x-delete-modal
                                :route="route('customers.destroy', $customer)"
                                label="customer {{ $customer->name }}" />
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">No customer data yet</td>
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
