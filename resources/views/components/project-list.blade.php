<div class="overflow-x-auto">
    <table id="projects-table" class="min-w-full bg-white shadow-md">
        <thead class="bg-gray-100  ">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">
                Name
            </th>
            @if(!auth()->user()->current_team_id)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Organization
                </th>
            @endif
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created At
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created By
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
                // Add the "Organization" column dynamically if current_team_id is not set
                    @if(!auth()->user()->current_team_id)
                { data: 'team', name: 'team', title: 'Organization' },
                    @endif
                { data: 'created_at', name: 'created_at', title: 'Created At' },
                { data: 'linkedOwner', name: 'linkedOwner', title: 'Created By' },
                { data: 'projectStatus', name: 'projectStatus', title: 'Status' },
                { data: 'action', name: 'action', orderable: false, searchable: false, title: 'Action' }
            ];

            $('#projects-table').DataTable({
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
                    searchPlaceholder: "Search projects...",
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


