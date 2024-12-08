<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">Create New Task</h1>

            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf

                <!-- Task Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Task Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name') }}" required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project -->
                <div class="mb-4">
                    <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                    <select name="project_id" id="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="" disabled>Select a project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', request('project_id')) == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="task_status_id" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="task_status_id" id="task_status_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="" disabled>Select a status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ old('task_status_id', 1) == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('task_status_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div class="mb-4">
                    <label for="task_priorities_id" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="task_priorities_id" id="task_priorities_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="" disabled selected>Select a priority</option>
                        @foreach ($priorities as $priority)
                            <option value="{{ $priority->id }}" {{ old('task_priorities_id') == $priority->id ? 'selected' : '' }}>
                                {{ $priority->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('task_priorities_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assigned Users -->
                @if ($currentTeamId)
                    <div class="mb-4">
                        <label for="user_ids" class="block text-sm font-medium text-gray-700">Assign Users</label>
                        @foreach ($users as $user)
                            @if ($user->id !== auth()->id()) <!-- Exclude the logged-in user -->
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" id="user_{{ $user->id }}">
                                <label for="user_{{ $user->id }}" class="ml-2">{{ $user->name }}</label>

                                <select name="roles[]" class="ml-4 border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        @if ($role->id !== 1) <!-- Exclude the author role -->
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
