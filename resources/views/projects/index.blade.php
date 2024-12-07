<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">Projects</h1>


            <a href="{{ route('projects.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Add New Project
            </a>


        <table id="projects-table" class="table table-bordered table-striped w-full">
            <thead>
            <tr>

                <th>Name</th>
                <th>Organization</th>
                <th>Created At</th>
                <th>Created By</th>
                <th>Status</th>
                <th>action</th>
            </tr>
            </thead>

        </table>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('#projects-table').DataTable({
                    processing: true,
                    serverSide: false, // Disable server-side processing
                    ajax: {
                        url: '{{ route('projects.api') }}',
                        type: 'GET',
                        error: function (xhr, error, thrown) {
                            console.error('AJAX Error:', error, thrown, xhr.responseText);
                        }
                    },
                    columns: [
                        // { data: 'id', name: 'id' },
                        { data: 'linkedName', name: 'linkedName' },
                        { data: 'team', name: 'team' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'linkedOwner', name: 'linkedOwner' },
                        { data: 'projectStatus', name: 'projectStatus' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }

                    ]
                });
            });
        </script>
    @endpush
</x-app-layout>
