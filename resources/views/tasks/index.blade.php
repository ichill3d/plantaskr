<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">Tasks</h1>

            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-200 px-4 py-2 text-left">Task Name</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Description</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Project</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Status</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Priority</th>
                    <th class="border border-gray-200 px-4 py-2 text-left">Assigned Users</th>
                    <th class="border border-gray-200 px-4 py-2 text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td class="border border-gray-200 px-4 py-2">{{ $task->name }}</td>
                        <td class="border border-gray-200 px-4 py-2">{{ $task->description }}</td>
                        <td class="border border-gray-200 px-4 py-2">{{ $task->project->name ?? 'N/A' }}</td>
                        <td class="border border-gray-200 px-4 py-2">{{ $task->status->name ?? 'N/A' }}</td>
                        <td class="border border-gray-200 px-4 py-2">{{ $task->priority->name ?? 'N/A' }}</td>
                        <td class="border border-gray-200 px-4 py-2">
                            @if ($task->users->isNotEmpty())
                                <ul>
                                    @foreach ($task->users as $user)
                                        <li>
                                            {{ $user->name }}
                                            <span class="text-sm text-gray-500">({{ $user->pivot->role_id }})</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-500">Unassigned</span>
                            @endif
                        </td>
                        <td class="border border-gray-200 px-4 py-2 text-center">
                            <a href="#" class="btn btn-sm btn-primary">View</a>
                            <a href="#" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="#" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border border-gray-200 px-4 py-2 text-center text-gray-500">No tasks found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
