<div x-data="{ open: false, searchQuery: '' }" class="relative">

    <!-- Display Assignees as Avatars -->
    <div class="flex -space-x-2 items-center">
        @foreach ($task->assignees as $assignee)
            <img
                title="{{ $assignee->name }}"
                src="{{ $assignee->profile_photo_url }}"
                class="object-cover w-6 h-6 rounded-full border-2 border-white"
            />
        @endforeach

        <!-- Add Assignee Button -->
        <button
            class="flex items-center justify-center w-6 h-6 bg-gray-200 text-gray-500 rounded-full border-2 border-white"
            @click="open = !open"
        >
            +
        </button>
    </div>

    <!-- Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition.opacity.duration.300ms
        class="fixed mt-2 bg-white border shadow-lg rounded-md w-64 max-h-64 overflow-y-auto z-10"
        style="display: none;"
    >
        <div class="flex items-end justify-between px-4 py-2 border-b">
            <div>Assign users to task</div>
            <button class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-2 py-1" @click="open = false">OK</button>
        </div>
        <!-- Search Box -->
        <div class="p-2">
            <input
                type="text"
                x-model="searchQuery"
                placeholder="Search users..."
                class="w-full px-2 py-1 border rounded-md text-sm focus:ring focus:ring-blue-500"
            />
        </div>

        <!-- User List -->
        @foreach ($filteredUsers as $user)
            <label
                class="flex items-center px-4 py-2 hover:bg-gray-100"
                x-show="searchQuery === '' || '{{ strtolower($user->name) }}'.includes(searchQuery.toLowerCase())"
            >

                    <input
                        type="checkbox"
                        wire:click="{{ $task->assignees->contains($user->id) ? 'unassignUser' : 'assignUser' }}({{ $user->id }})"
                        {{ $task->assignees->contains($user->id) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                    />
                    <img
                        src="{{ $user->profile_photo_url }}"
                        alt="{{ $user->name }}"
                        class="w-6 h-6 rounded-full ml-2"
                    />
                    <span class="ml-2 text-sm">{{ $user->name }}</span>

            </label>
        @endforeach
    </div>
</div>
