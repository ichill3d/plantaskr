<div x-data="{ open: false }" class="relative">
    <!-- Priority Display -->
    <button
        @click="open = !open"
        class="px-2 py-1 text-white rounded-md"
        style="background-color: {{ $task->priority_color ?? '#ccc' }}"
    >
        {{ $task->priority->name ?? 'N/A' }}
    </button>

    <!-- Priority Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition.opacity.duration.300ms
        class="absolute mt-1 bg-white border rounded-md shadow-lg w-32 z-10"
        style="display: none;"
    >
        @foreach ($priorities as $priority)
            <button
                wire:click.prevent="updatePriority({{ $priority->id }})"
                @click="open = false"
                class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
            >
                {{ $priority->name }}
            </button>
        @endforeach
    </div>
</div>
