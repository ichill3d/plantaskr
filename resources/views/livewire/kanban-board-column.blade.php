<div
    class="bg-gray-100 rounded-lg shadow-md h-full flex flex-col overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 max-h-[calc(100vh-13rem)] "
    @dragover.prevent="
        placeholderStatus = {{ $statusId }};
        const items = Array.from($event.currentTarget.querySelectorAll('[draggable=true]'));
        let closest = null;
        let closestOffset = Number.NEGATIVE_INFINITY;

        items.forEach((item, index) => {
            const rect = item.getBoundingClientRect();
            const offset = $event.clientY - rect.top - rect.height / 2;

            if (offset < 0 && offset > closestOffset) {
                closestOffset = offset;
                closest = index;
            }
        });

        placeholderIndex = (closest !== null ? closest : items.length) - 1;
    "
    @drop="
        if (draggingTask !== null) {
            Livewire.dispatch('updateTaskStatus', {
                taskId: draggingTask,
                statusId: {{ $statusId }},
                position: placeholderIndex + 1
            });

            draggingTask = null;
            placeholderIndex = null;
            placeholderStatus = null;
        }
    "
>

    <div class="flex-1 space-y-2 p-2 relative overflow-y-auto scrollbar-track-rounded-lg scrollbar-thumb-rounded-lg scrollbar-track-opacity-75 scrollbar-thumb-opacity-75 scrollbar-thin shadow-bottom">
        @foreach ($tasks as $index => $task)
            <!-- Placeholder Above -->
            <div
                x-show="placeholderStatus === {{ $statusId }} && placeholderIndex === {{ $index - 1 }}"
                class="h-4 bg-blue-200 rounded-md border-2 border-blue-400 my-1"
                style="border-style: dashed; transition: all 0.2s ease-in-out;"
            ></div>

            <!-- Task Card -->
            <livewire:kanban-board-card
                :task="$task"
                wire:key="task-{{ $task['id'] }}"
            />
        @endforeach

        <!-- Placeholder at the End -->
        <div
            x-show="placeholderStatus === {{ $statusId }} && placeholderIndex === {{ count($tasks) - 1 }}"
            class="h-4 bg-blue-200 rounded-md border-2 border-blue-400 my-1"
            style="border-style: dashed; transition: all 0.2s ease-in-out;"
        ></div>
    </div>
</div>
