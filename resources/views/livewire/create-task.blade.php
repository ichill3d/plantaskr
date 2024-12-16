<div>

    <!-- Trigger Button -->
    <button wire:click="$set('showModal', true)" class="bg-gray-200 p-2 rounded-lg hover:bg-gray-300">Create New Task</button>

    <!-- Modal -->
    @if($showModal)
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40" wire:click="$set('showModal', false)"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6">
                    <h2 class="text-xl font-bold mb-4">Create New Task</h2>

                    <form wire:submit.prevent="save">
                        <!-- Task Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Task Name</label>
                            <input wire:model="name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Priority -->
                        <div class="mb-4">
                            <label for="priority_id" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select wire:model="priority_id" id="priority_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Select priority</option>
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                @endforeach
                            </select>
                            @error('priority_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Project -->
                        <select wire:model="project_id" id="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="" disabled {{ $project_id === null ? 'selected' : '' }}>Select a project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{ $project->id == $project_id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="mb-4">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Assigned Users -->
                        @if ($currentTeamId)
                            <div class="mb-4">
                                <label for="user_ids" class="block text-sm font-medium text-gray-700">Assign Users</label>
                                @foreach ($users as $user)

                                    <div class="flex items-center mb-2">
                                        <input  wire:model="user_ids" type="checkbox" name="user_ids[]" value="{{ $user->id }}" id="user_{{ $user->id }}">
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

                                @endforeach
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end">
                            <button type="button" wire:click="$set('showModal', false)" class="btn btn-secondary mr-2">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
