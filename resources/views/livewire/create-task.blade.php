<div>
    <!-- Trigger Button -->
    <button wire:click="$set('showModal', true)" class="bg-gray-200 p-2 rounded-lg hover:bg-gray-300">Create New Task</button>

    <!-- Modal -->
    @if($showModal)
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
                            <x-quill-editor :key="$showModal" name="description" />
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
                        <div class="mb-4">
                            <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                            <select wire:model="project_id" id="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="" >Select a project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                            @error('project_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Due Date -->
                        <div class="mb-4">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <div>
                                <input
                                    type="text"
                                    id="due_date"
                                    wire:model.live="due_date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm flatpickr"
                                    placeholder="Select a date"
                                />
                            </div>


                            @error('due_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <!-- Push Flatpickr Initialization Script -->
                        @script
                            <script>

                                Livewire.on('showTheModal', () => {

                                    setTimeout(() => {
                                        flatpickr(".flatpickr", {
                                            dateFormat: "Y-m-d", // Adjust as per your database format
                                            allowInput: false,  // Prevent manual typing
                                        });
                                    }, 100); // Short delay (100ms) to ensure DOM is updated
                                });
                            </script>
                        @endscript

                        <!-- Assign Users -->
                        @if ($currentTeamId)
                            <div x-data="{
            assignOpen: false,
            search: '',
            selectedUsers: @entangle('user_ids'),
            allUsers: {{ $users->map(fn($user) => ['id' => $user->id, 'name' => $user->name, 'profile_photo_path' => $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null]) }}
        }"
                                 class="mb-4 relative">
                                <div class="flex justify-between items-center">
                                    <div for="assign_users" class="text-sm font-medium text-gray-700 flex items-start space-x-2 h-10">
                                        Assigned to:
                                        <div id="assigned_users" class="flex items-start space-x-2">
                                            <!-- Display Selected Users -->
                                            <template x-for="userId in selectedUsers" :key="userId">
                                                <div class="relative w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center">
                                                    <template x-if="allUsers.find(user => user.id === userId)?.profile_photo_path">
                                                        <img :src="allUsers.find(user => user.id === userId).profile_photo_path"
                                                             :alt="allUsers.find(user => user.id === userId).name"
                                                             class="w-full h-full object-cover">
                                                    </template>
                                                    <template x-if="!allUsers.find(user => user.id === userId)?.profile_photo_path">
                                <span class="text-sm font-semibold text-gray-700"
                                      x-text="allUsers.find(user => user.id === userId).name[0].toUpperCase()"></span>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <a href="#" @click="assignOpen = !assignOpen" class="text-blue-500 hover:underline">
                                        Select Members
                                    </a>
                                </div>

                                <!-- Dropdown -->
                                <div x-show="assignOpen"
                                     class="w-full rounded-md py-2 px-4 border border-gray-300">
                                    <div>
                                        <h3 class="font-semibold mb-2">Select Members</h3>
                                        <!-- Search Input -->
                                        <input x-model="search" type="text" placeholder="Search users..."
                                               class="mb-2 w-full border rounded-md px-2 py-1 text-sm" />

                                        <!-- User List with Checkboxes -->
                                        <ul class="max-h-24 overflow-y-auto">
                                            @foreach ($users as $user)
                                                <li x-show="!search || '{{ strtolower($user->name) }}'.includes(search.toLowerCase())"
                                                    class="flex items-center space-x-2 mb-2">
                                                    <!-- Checkbox and Name -->
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox"
                                                               @change="if ($event.target.checked) {
                                            selectedUsers.push({{ $user->id }});
                                        } else {
                                            selectedUsers = selectedUsers.filter(userId => userId !== {{ $user->id }});
                                        }"
                                                               :checked="selectedUsers.includes({{ $user->id }})" />
                                                        <!-- Avatar -->
                                                        <div class="relative w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center">
                                                            @if ($user->profile_photo_path)
                                                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                                            @else
                                                                <span class="text-sm font-semibold text-gray-700">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                                            @endif
                                                        </div>
                                                        <span>{{ $user->name }}</span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif



                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-between">

                            <button type="button" wire:click="$set('showModal', false)" class="btn btn-secondary mr-2">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
