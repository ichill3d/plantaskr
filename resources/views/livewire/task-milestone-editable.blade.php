<div>
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-2 border-b">
        <div>Change Task Milestone</div>
        <button
            class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-2 py-1"
            @click="$dispatch('milestoneupdated', { taskId: {{ $task->id }} })"
        >
            OK
        </button>
    </div>

    <!-- Milestone List -->
    <div class="max-h-48 overflow-y-auto divide-y">
        <!-- No Milestone Option -->
        <div
            wire:click="updateMilestone(null)"
            @click="$dispatch('milestoneupdated', { taskId: {{ $task->id }} })"
            class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer {{ $selectedMilestone === null ? 'bg-gray-200 font-bold' : '' }}"
        >
            <span class="ml-2 text-sm">No Milestone</span>
        </div>

        <!-- List of Milestones -->
        @foreach ($milestones as $milestone)
            <div
                wire:click="updateMilestone({{ $milestone->id }})"
                @click="$dispatch('milestoneupdated', { taskId: {{ $task->id }} })"
                class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer {{ $selectedMilestone == $milestone->id ? 'bg-gray-200 font-bold' : '' }}"
            >
                <span class="ml-2 text-sm">{{ $milestone->name }}</span>
            </div>
        @endforeach
    </div>


    @if (session()->has('success'))
        <div class="mt-2 text-green-500 text-sm">{{ session('success') }}</div>
    @endif

    @if (session()->has('error'))
        <div class="mt-2 text-red-500 text-sm">{{ session('error') }}</div>
    @endif
</div>
