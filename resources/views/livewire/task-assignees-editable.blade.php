<div>
    <div class="flex items-center justify-between px-4 py-2 border-b">
        <div>Assign users to task</div>
        <button
            class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-2 py-1"
            @click ="$dispatch('assigneesupdated', { taskId: {{ $task->id }} })"
        >
            OK
        </button>
    </div>

    <!-- Search Box -->
    <div class="p-2">
        <input
            type="text"
            wire:model="searchQuery"
            placeholder="Search users..."
            class="w-full px-2 py-1 border rounded-md text-sm focus:ring focus:ring-blue-500"
        />
    </div>

    <!-- User List -->
    <div class="max-h-48 overflow-y-auto divide-y">
        @foreach ($filteredUsers as $user)
            <label class="flex items-center px-4 py-2 hover:bg-gray-100">
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
