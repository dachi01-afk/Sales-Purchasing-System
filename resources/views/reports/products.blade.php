<x-app-layout>
    <x-slot name="header">Product Report</x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4">
            <table id="products-table" class="w-full display">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Standard Price</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @push('scripts')
<script>
$(document).ready(function() {
    $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("reports.products.data", [], false) }}',
        columns: [
            { data: 'sku', name: 'sku' },
            { data: 'name', name: 'name' },
            { data: 'standard_price', name: 'standard_price', className: 'text-right font-medium' }
        ],
        dom: '<"flex justify-between items-center mb-4"Bf>rt<"flex justify-between items-center mt-4"ip>',
        buttons: [
            { extend: 'excel', text: 'Export Excel', className: 'bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg' },
            { extend: 'csv', text: 'Export CSV', className: 'bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium px-4 py-2 rounded-lg' }
        ],
        order: [[0, 'asc']],
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
