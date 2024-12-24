<div class="relative h-full overflow-y-hidden max-h-[calc(100vh-10rem)]">

    <!-- Status Header -->
    <div class="grid sticky top-0 z-30 grid-cols-4 gap-4 overflow-x-hidden bg-white shadow-md">
        @foreach ($statuses as $statusId => $statusName)
            <div class="text-lg font-bold p-2 text-center">
                {{ $statusName }}
            </div>
        @endforeach
    </div>

    <!-- Task Board -->
    <div
        class="grid grid-cols-4 gap-4 overflow-x-hidden h-full"
        x-data="{
            draggingTask: null,
            draggingStatus: null,
            placeholderIndex: null,
            placeholderStatus: null
        }"
        x-on:refreshBoard.window="$nextTick(() => console.log('Board refreshed successfully'))"
    >
        @foreach ($statuses as $statusId => $statusName)
            <div
                class="bg-gray-100 rounded-lg shadow-md h-full overflow-y-auto flex flex-col scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
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

        setTimeout(() => {
            Livewire.dispatch('refreshBoard');
        }, 50);

        draggingTask = null;
        placeholderIndex = null;
        placeholderStatus = null;
    }
"

            >

                <!-- Task List -->
                <div class="h-full space-y-1 p-2 flex-1 relative">
                    @foreach ($tasks[$statusId] ?? [] as $index => $task)
                        <!-- Placeholder Above -->
                        <div
                            x-show="placeholderStatus === {{ $statusId }} && placeholderIndex === {{ $index - 1 }}"
                            class="h-4 bg-blue-200 rounded-md border-2 border-blue-400 my-1"
                            style="border-style: dashed; transition: all 0.2s ease-in-out;"
                        ></div>

                        <!-- Task Card -->
                        <div
                            wire:key="task-{{ $task['id'] }}-{{ $task['task_status_id'] }}"
                            class="bg-white rounded-xl border-2 border-t-0 border-gray-300 shadow cursor-move text-sm xl:text-base transition-transform duration-300 ease-in-out"
                            style="border-top-color: {{ $task['project']['color'] }};"
                            draggable="true"
                            @dragstart="
                    draggingTask = {{ $task['id'] }};
                    draggingStatus = {{ $task['task_status_id'] }};
                "
                            :class="{
                    'opacity-50 scale-95': draggingTask === {{ $task['id'] }},
                }"
                        >
                            <!-- Project Name -->
                            <div class="overflow-y-hidden border-t-2 border-t-gray-400 h-6 text-xs text-center rounded-t-xl p-1 opacity-50"
                                 style="background-color: {{$task['project']['color']}}; color: {{ get_contrast_color($task['project']['color']) }} ">
                                <span class="opacity-80">{{ $task['project']['name'] }}</span>
                            </div>

                            <!-- Task Name -->
                            <div class="p-2 border-b bg-gray-50 font-semibold mb-2">
                                {{ $task['name'] }}
                            </div>

                            <div class="p-2">

                                <!-- Assignees -->
                                <div class="border-b border-gray-300 pb-2 mb-2 flex items-center justify-between">
                                    <div>Assignees:</div>
                                    <livewire:task-assignees-editable :taskId="$task['id']" wire:key="task-assignees-{{ $task['id'] }}-{{ now()->timestamp }}" />
                                </div>

                                <!-- Priority -->
                                <div class="border-b border-gray-300 pb-2 mb-2 flex items-center justify-between">
                                    <div>Priority:</div>
                                    <livewire:task-priority-editable :taskId="$task['id']" wire:key="task-priority-{{ $task['id'] }}-{{ now()->timestamp }}" />
                                </div>

                                <!-- Open Task Button -->
                                <div class="flex items-center justify-center">
                                    <a
                                        href="{{ route('organizations.board.tasks.show', [
                                'id' => $task['team']['id'],
                                'organization_alias' => $task['team']['alias'],
                                'task_id' => $task['id']]) }}"
                                        @click.prevent="
                                history.pushState({}, '', '{{ route('organizations.board.tasks.show', ['id' => $task['team']['id'], 'organization_alias' => $task['team']['alias'], 'task_id' => $task['id']]) }}');
                                Livewire.dispatchTo('task-modal', 'openTaskModal', { taskId: {{ $task['id'] }} });
                                setTimeout(() => window.dispatchEvent(new CustomEvent('modal-opened')), 100);
                            "
                                        class="bg-gray-200 px-2 py-1 text-gray-600 rounded-md hover:bg-blue-600 hover:text-white text-sm"
                                    >
                                        Open Task
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Placeholder at the End of Column -->
                    <div
                        x-show="placeholderStatus === {{ $statusId }} && placeholderIndex === {{ count($tasks[$statusId] ?? []) - 1 }}"
                        class="h-4 bg-blue-200 rounded-md border-2 border-blue-400 my-1"
                        style="border-style: dashed; transition: all 0.2s ease-in-out;"
                    ></div>
                </div>
            </div>

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
