    <div>
        <div
        wire:key="task-{{ $task->id }}-{{ $task->task_status_id }}"
        class="bg-white rounded-xl border-2 border-t-0 border-gray-300 shadow cursor-move text-sm xl:text-base transition-transform duration-300 ease-in-out"
        style="border-top-color: {{ $task->project->color }};"
        draggable="true"
        data-task-id="{{ $task->id }}"
        @dragstart="
        draggingTask = {{ $task->id }};
        draggingStatus = {{ $task->task_status_id }};
    "
        @dragend="
        Livewire.dispatch('updateTaskStatus', {
            taskId: draggingTask,
            statusId: placeholderStatus,
            position: placeholderIndex + 1
        });
        draggingTask = null;
        placeholderIndex = null;
        placeholderStatus = null;
    "
        :class="{
        'opacity-50 scale-95': draggingTask === {{ $task->id }},
    }"
    >
        <!-- Project Name -->
        <div class="overflow-y-hidden border-t-2 border-t-gray-400 h-6 text-xs text-center rounded-t-xl p-1 opacity-50"
             style="background-color: {{ $task->project->color }}; color: {{ get_contrast_color($task->project->color) }} ">
            <span class="opacity-80">{{ $task->project->name }}</span>
        </div>

        <!-- Task Name -->
        <div class="p-2 border-b bg-gray-50 font-semibold mb-2">
            {{ $task->name }}
        </div>

        <div class="p-2">

            <!-- Assigned Users Section -->
            <div>
                <div class="font-semibold">Assigned Users:</div>
                <div
                    x-data="{ open: false }"
                    x-on:assigneesupdated.window="if ($event.detail.taskId == {{ $task->id }}) open = false"
                    class="relative"
                >
                    <!-- Display Assignees Avatars Initially -->
                    <div class="flex -space-x-2 items-center">
                        @foreach ($task->assignees as $assignee)
                            <img
                                title="{{ $assignee->name }}"
                                src="{{ $assignee->profile_photo_url }}"
                                class="object-cover w-6 h-6 rounded-full border-2 border-white"
                            />
                        @endforeach

                        <!-- Add Assignee Button -->
                        <button
                            class="flex items-center justify-center w-6 h-6 bg-gray-200 text-gray-500 rounded-full border-2 border-white"
                            @click="open = !open"
                        >
                            +
                        </button>
                    </div>

                    <!-- Lazy-Loaded Dropdown -->
                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition.opacity.duration.300ms
                        class="absolute mt-2 bg-white border shadow-lg rounded-md w-64 max-h-64 overflow-y-auto z-10"
                        style="display: none;"
                    >
                        <livewire:task-assignees-editable
                            lazy
                            :taskId="$task->id"
                            wire:key="task-{{ $task->id }}-assignees"
                        />
                    </div>
                </div>
            </div>

            <!-- Priority Section -->
            <div>
                <div class="font-semibold">Priority:</div>
                <div
                    x-data="{ open: false }"
                    x-on:taskpriorityselected.window="if ($event.detail.taskId == {{ $task->id }}) open = false"
                    class="relative"
                >
                    <!-- Button for Displaying Current Priority -->
                    <button
                        @click="open = !open"
                        class="px-2 py-1 text-white rounded-md"
                        style="background-color: {{ $task->priority_color ?? '#ccc' }};"
                    >
                        <span>{{ $task->priority->name }}</span>
                    </button>

                    <!-- Dropdown with Livewire Editable Component -->
                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition.opacity.duration.300ms
                        class="absolute bg-white border rounded-md shadow-lg w-32 z-10"
                        style="display: none;"
                    >
                        <livewire:task-priority-editable
                            :taskId="$task->id"
                            wire:key="task-{{ $task->id }}-priority"
                        />
                    </div>
                </div>
            </div>

            <!-- Open Task Button -->
            <div class="flex items-center justify-center">
                <a
                    href="{{ route('organizations.board.tasks.show', [
                                'id' => $task->team->id,
                                'organization_alias' => $task->team->alias,
                                'task_id' => $task->id,
                                ])
                            }}"
                    @click.prevent="
                                history.pushState({}, '', '{{ route('organizations.board.tasks.show', ['id' => $task->team->id, 'organization_alias' => $task->team->alias, 'task_id' => $task->id]) }}');
                                Livewire.dispatchTo('task-modal', 'openTaskModal', { taskId: {{ $task->id }} });
                                setTimeout(() => window.dispatchEvent(new CustomEvent('modal-opened')), 100);
                            "
                    class="bg-gray-200 px-2 py-1 text-gray-600 rounded-md hover:bg-blue-600 hover:text-white text-sm"
                >
                    Open Task
                </a>
            </div>
        </div>
    </div>
</div>
