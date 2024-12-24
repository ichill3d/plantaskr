<div>
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-2 border-b">
        <div>Set Due Date</div>
        <button
            wire:click="updateDueDate()"
            @click="$dispatch('duedateupdated', { taskId: {{ $task->id }} })"
            class="bg-green-500 hover:bg-green-600 text-white rounded-lg px-3 py-1"
        >
            Save
        </button>
    </div>

    <!-- Date Picker -->
    <div class="p-4">
        <input
            type="date"
            wire:model.live="dueDate"
            class="w-full px-2 py-1 border rounded-md text-sm focus:ring focus:ring-blue-500"
        />
    </div>

    <!-- Clear Due Date Option -->
    <div
        wire:click="updateDueDate(true)"
        @click="$dispatch('duedateupdated', { taskId: {{ $task->id }} })"
        class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer text-red-500"
    >
        Clear Due Date
    </div>

    @if (session()->has('success'))
        <div class="mt-2 text-green-500 text-sm">{{ session('success') }}</div>
    @endif

    @if (session()->has('error'))
        <div class="mt-2 text-red-500 text-sm">{{ session('error') }}</div>
    @endif
</div>
