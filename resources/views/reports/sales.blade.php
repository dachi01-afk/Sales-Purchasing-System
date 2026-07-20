<x-app-layout>
    <x-slot name="header">Sales Report</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-4">
        <div class="p-4">
            <form method="GET" class="flex gap-4 items-end">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">From Date</label>
                    <input type="date" name="start" value="{{ request('start') }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-2">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">To Date</label>
                    <input type="date" name="end" value="{{ request('end') }}" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-2">
                </div>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">Filter</button>
                <a href="{{ route('reports.sales') }}" class="text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 font-medium rounded-lg text-sm px-4 py-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total SO</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSO }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total DO</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDO }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Invoice (Rp)</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalInvoice, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Receipts (Rp)</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalReceipts, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4">
            <table id="sales-table" class="w-full display">
                <thead>
                    <tr>
                        <th>#SO</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @push('scripts')
<script>
$(document).ready(function() {
    $('#sales-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("reports.sales.data", [], false) }}',
            data: function(d) {
                d.start = '{{ request("start") }}';
                d.end = '{{ request("end") }}';
            }
        },
        columns: [
            { data: 'so_id', name: 'id', className: 'font-medium' },
            { data: 'date', name: 'date' },
            { data: 'customer_name', name: 'customer.name' },
            { data: 'items_count', name: 'items_count', orderable: false, searchable: false },
            { data: 'status', name: 'status' }
        ],
        dom: '<"flex justify-between items-center mb-4"Bf>rt<"flex justify-between items-center mt-4"ip>',
        buttons: [
            { extend: 'excel', text: 'Export Excel', className: 'bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg' },
            { extend: 'csv', text: 'Export CSV', className: 'bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium px-4 py-2 rounded-lg' }
        ],
        order: [[1, 'desc']],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            emptyTable: "No data available",
            zeroRecords: "No matching records found"
        }
    });
});
</script>
    @endpush
</x-app-layout>
