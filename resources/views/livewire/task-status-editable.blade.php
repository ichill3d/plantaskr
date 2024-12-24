<div x-data="{ open: false }" class="relative">
    <!-- Status Display -->
    <button
        @click="open = !open"
        class="text-sm font-medium text-blue-600 hover:underline focus:outline-none"
    >
        {{ $task->status->name }}
    </button>

    <!-- Status Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        class="absolute mt-2 w-48 bg-white shadow-md rounded-md z-10"
        style="display: none;"
    >
        @foreach ($statuses as $status)
            <a
                href="#"
                wire:click.prevent="updateStatus({{ $status->id }})"
                @click="open = false"
                class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
            >
                {{ $status->name }}
            </a>
        @endforeach
    </div>
</div>
