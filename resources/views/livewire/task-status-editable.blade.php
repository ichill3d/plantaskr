<div>
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-2 border-b">
        <div>Change Task Status</div>
        <button
            class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-2 py-1"
            @click="$dispatch('statusupdated', { taskId: {{ $task->id }} })"
        >
            OK
        </button>
    </div>

    <!-- Status List -->
    <div class="max-h-48 overflow-y-auto divide-y">
        @foreach ($statuses as $status)

                <div
                    wire:click="updateStatus({{ $status->id }})"
                    @click="$dispatch('statusupdated', { taskId: {{ $task->id }} })"
                    {{ $selectedStatus == $status->id ? 'checked' : '' }}
                    class="flex items-center px-4 py-2 hover:bg-gray-100  cursor-pointer rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                >
                    <span class="ml-2 text-sm">{{ $status->name }}</span>
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
