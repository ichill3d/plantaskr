<div x-data="{ open: false }" class="relative">
    <!-- Current Milestone Display -->
    <button
        @click="open = !open"
        class="text-sm hover:underline hover:text-blue-600"
    >
        {{ $task->milestone->name ?? 'N/A' }}
    </button>

    <!-- Milestone Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition.opacity.duration.300ms
        class="absolute mt-1 bg-white border shadow-lg rounded-md w-48 max-h-40 overflow-y-auto z-10"
        style="display: none;"
    >
        @foreach ($milestones as $milestone)
            <button
                wire:click.prevent="updateMilestone({{ $milestone->id }})"
                @click="open = false"
                class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
            >
                {{ $milestone->name }}
            </button>
        @endforeach
    </div>

</div>
