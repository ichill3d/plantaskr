<!-- Task Card -->
<div
    wire:key="task-{{ $task->id }}-{{ $task->task_status_id }}"
    class="bg-white rounded-xl border-2 border-t-0 border-gray-300 shadow cursor-move text-sm xl:text-base transition-transform duration-300 ease-in-out"
    style="border-top-color: {{ $task->project->color }};"
    draggable="true"
    @dragstart="
                    draggingTask = {{ $task->id }};
                    draggingStatus = {{ $task->task_status_id }};
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

        <!-- Assignees -->
        <div class="border-b border-gray-300 pb-2 mb-2 flex items-center justify-between">
            <div>Assignees:</div>
            <livewire:task-assignees-editable :taskId="$task->id" wire:key="task-assignees-{{ $task->id }}-{{ now()->timestamp }}" />
        </div>

        <!-- Priority -->
        <div class="border-b border-gray-300 pb-2 mb-2 flex items-center justify-between">
            <div>Priority:</div>
            <livewire:task-priority-editable :taskId="$task->id" wire:key="task-priority-{{ $task->id }}-{{ now()->timestamp }}" />
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
</div><!-- Task Card -->
<div
    wire:key="task-{{ $task->id }}-{{ $task->task_status_id }}"
    class="bg-white rounded-xl border-2 border-t-0 border-gray-300 shadow cursor-move text-sm xl:text-base transition-transform duration-300 ease-in-out"
    style="border-top-color: {{ $task->project->color }};"
    draggable="true"
    @dragstart="
                    draggingTask = {{ $task->id }};
                    draggingStatus = {{ $task->task_status_id }};
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

        <!-- Assignees -->
        <div class="border-b border-gray-300 pb-2 mb-2 flex items-center justify-between">
            <div>Assignees:</div>
            <livewire:task-assignees-editable :taskId="$task->id" wire:key="task-assignees-{{ $task->id }}-{{ now()->timestamp }}" />
        </div>

        <!-- Priority -->
        <div class="border-b border-gray-300 pb-2 mb-2 flex items-center justify-between">
            <div>Priority:</div>
            <livewire:task-priority-editable :taskId="$task->id" wire:key="task-priority-{{ $task->id }}-{{ now()->timestamp }}" />
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
