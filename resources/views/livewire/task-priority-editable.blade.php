<div style="">
    <div class="text-sm font-medium mb-2 px-2">Select Priority:</div>
    <div class="border rounded-md shadow-md overflow-hidden">
        @foreach ($priorities as $priority)
            <button
                wire:click.prevent="updatePriority({{ $priority->id }})"
                @click="$dispatch('taskpriorityselected', { taskId: {{ $task->id }} })"
                class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $selectedPriority == $priority->id ? 'bg-gray-200 font-bold' : '' }}"
                style="background-color: {{ $priority->color ?? '#fff' }};"
            >
                {{ $priority->name }}
            </button>
        @endforeach
    </div>

    @if (session()->has('success'))
        <div class="mt-2 text-green-500">{{ session('success') }}</div>
    @endif

    @if (session()->has('error'))
        <div class="mt-2 text-red-500">{{ session('error') }}</div>
    @endif
</div>
