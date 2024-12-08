<div class="overflow-x-auto">
    <table id="tasks-table" class="min-w-full bg-white shadow-md">
        <thead class="bg-gray-100">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">
                Name
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Project
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created At
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Assigned To
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
            </th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">
                Action
            </th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 border border-gray-300">
        <!-- DataTables will populate rows here -->
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const columns = [
                { data: 'linkedName', name: 'linkedName', title: 'Name' },
                { data: 'linkedProject', name: 'linkedProject', title: 'Project' },
                { data: 'created_at', name: 'created_at', title: 'Created At' },
                { data: 'linkedAssignees', name: 'linkedAssignees', title: 'Assigned To' },
                { data: 'taskStatus', name: 'taskStatus', title: 'Status' },
                { data: 'action', name: 'action', orderable: false, searchable: false, title: 'Action' }
            ];

            $('#tasks-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{{ $ajaxUrl }}',
                    type: 'GET',
                    error: function (xhr, error, thrown) {
                        console.error('AJAX Error:', error, thrown, xhr.responseText);
                    }
                },
                columns: columns,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search tasks...",
                    paginate: {
                        previous: "←",
                        next: "→"
                    }
                },
                dom: '<"flex justify-between items-center mb-4"<"length-menu"l><"search-bar"f>>t<"flex justify-between items-center mt-4"<"info"i><"pagination"p>>',
                createdRow: function (row, data, dataIndex) {
                    // Add TailwindCSS styles for hover and rounded corners
                    $(row).addClass('hover:bg-gray-50 hover:shadow-sm');

                    // Add rounded corners to the first and last cells of the row
                    $(row).find('td:first').addClass('rounded-tl-lg rounded-bl-lg');
                    $(row).find('td:last').addClass('rounded-tr-lg rounded-br-lg');
                },
                rowCallback: function (row, data, dataIndex) {
                    // Ensure styling is reapplied after DataTables redraws
                    $(row).addClass('hover:bg-gray-50 hover:shadow-sm');
                    $(row).find('td:first').addClass('rounded-tl-lg rounded-bl-lg');
                    $(row).find('td:last').addClass('rounded-tr-lg rounded-br-lg');
                }
            });
        });
    </script>
@endpush
