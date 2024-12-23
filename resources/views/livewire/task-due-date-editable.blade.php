<div x-data="{ open: false, date: '{{ $dueDate }}' }" class="relative">
    <!-- Display Current Due Date -->
    <button
        @click="open = !open"
        class="text-sm text-gray-700 hover:underline hover:text-blue-600"
    >
        {{ $task->due_date ? $task->due_date->format('d M Y') : 'N/A' }}
    </button>

    <!-- Date Picker Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition.opacity.duration.300ms
        class="absolute right-0 mt-1 bg-white border shadow-lg rounded-md p-3 w-52 z-10"
    >
        <input
            type="date"
            x-model="date"
            class="w-full p-1 border rounded-md text-sm focus:ring focus:ring-blue-500"
        />
        <div class="flex justify-end mt-3 space-x-2">
            <button
                @click="open = false"
                class="px-2 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-md"
            >
                Cancel
            </button>
            <button
                @click="$wire.updateDueDate(date); open = false"
                class="px-2 py-1 text-sm text-white bg-blue-500 rounded-md hover:bg-blue-600"
            >
                Save
            </button>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div
            x-data="{ visible: true }"
            x-init="setTimeout(() => visible = false, 2000)"
            x-show="visible"
            x-transition.opacity.duration.500ms
            class="mt-2 text-xs text-green-500"
        >
            {{ session('success') }}
        </div>
    @endif
</div>
