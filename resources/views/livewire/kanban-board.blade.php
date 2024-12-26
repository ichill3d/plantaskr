<div class="relative h-full overflow-y-hidden max-h-[calc(100vh-10rem)]">

    <!-- Status Header -->
    <div class="grid sticky top-0 z-30 grid-cols-4 gap-4 overflow-x-hidden bg-white shadow-md">
        @foreach ($statuses as $status)
            <div class="text-lg font-bold p-2 text-center">
                {{ $status->name }}
            </div>
        @endforeach
    </div>

    <!-- Task Board -->
    <div
        class="grid grid-cols-4 gap-4 overflow-y-hidden"
        x-data="{
            draggingTask: null,
            draggingStatus: null,
            placeholderIndex: null,
            placeholderStatus: null,
        }"
        x-on:refreshBoard.window="$nextTick(() => console.log('Board refreshed successfully'))"
    >
        @foreach ($statuses as $status)
            <livewire:kanban-board-column
                :teamId="$teamId"
                :statusId="$status->id"
                wire:key="status-{{ $status->id }}"
                x-on:dragstart="draggingTask = $event.target.dataset.taskId; draggingStatus = {{ $status->id }};"
                x-on:dragend="

                    Livewire.dispatch('updateTaskStatus', {
                        taskId: draggingTask,
                        statusId: placeholderStatus,
                        position: placeholderIndex + 1
                    });
                    draggingTask = null;
                    placeholderIndex = null;
                    placeholderStatus = null;
                "
            />
        @endforeach
    </div>

    <livewire:task-modal :taskId="$selectedTask?->id" />

    <script>
        window.addEventListener('popstate', () => {
            const taskId = window.location.pathname.split('/').filter(Boolean).pop();
            if (!isNaN(taskId) && taskId !== '') {
                Livewire.dispatch('openTaskModal', { taskId: parseInt(taskId) });
            } else {
                Livewire.dispatch('closeTaskModal');
            }
        });
    </script>
</div>
