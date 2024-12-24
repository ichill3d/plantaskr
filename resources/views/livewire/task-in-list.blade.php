<div class="border rounded-md bg-white shadow-md hover:bg-gray-50 hover:text-blue-600 text-gray-800">
    <!-- Task Name and Project -->
    <a
        href="{{ route('organizations.tasks.show', ['id' => $task->team->id, 'organization_alias' => $task->team->alias, 'task_id' => $task->id]) }}"
        x-data
        @click.prevent="
            history.pushState({}, '', '{{ route('organizations.tasks.show', ['id' => $task->team->id, 'organization_alias' => $task->team->alias, 'task_id' => $task->id]) }}');
            console.log('Task clicked : {{ $task->id }}');
            Livewire.dispatch('openTaskModal', { taskId: {{ $task->id }} });
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('modal-opened'));
            }, 100);
        "
        class="flex justify-between items-start rounded-t-md border-b hover:bg-gray-100 border-t-4"
        style="border-top-color: {{ $task->project->color }};">
        <div class="font-bold px-4 py-2">{{ $task->name }}</div>
        <div class="rounded-bl-full px-4 pb-1 pl-6"
             style="background-color: {{ $task->project->color }}; color: {{ get_contrast_color($task->project->color) }};">
            {{ $task->project->name }}
        </div>
    </a>

    <!-- Task Details -->
    <div class="grid gap-4 text-sm text-gray-600 px-4 py-2"
         style="grid-template-columns: repeat({{ count($columns) }}, minmax(0, 1fr));">

        @if($columns['priority'])
            <div class="col-span-1">
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
        @endif



    @if($columns['created_time'])
            <div class="col-span-1 {{ $lastColumn === 'created_time' ? 'text-right pr-4' : '' }}">
                {{ $task->created_at->format('d M Y') }}
            </div>
        @endif

        @if($columns['created_by'])
            <div class="col-span-1 {{ $lastColumn === 'created_by' ? 'text-right pr-4' : '' }}">
                {{ $task->creator->name ?? 'N/A' }}
            </div>
        @endif

            @if($columns['assigned_users'])
                <div class="col-span-1">
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
            @endif



            @if($columns['status'])
                <div class="col-span-1 {{ $lastColumn === 'status' ? 'text-right pr-4' : '' }}">
                    <div
                        x-data="{ open: false }"
                        x-on:statusupdated.window="if ($event.detail.taskId == {{ $task->id }}) open = false"
                        class="relative"
                    >
                        <!-- Display Current Status -->
                        <span class="text-blue-500 hover:underline cursor-pointer" @click="open = !open">
                            {{ $task->status->name ?? 'N/A' }}
                        </span>

                        <!-- Lazy-Loaded Dropdown -->
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition.opacity.duration.300ms
                            class="absolute mt-1 bg-white border shadow-lg rounded-md w-64 z-10"
                            style="display: none;"
                        >
                            <livewire:task-status-editable
                                lazy
                                :taskId="$task->id"
                                wire:key="task-{{ $task->id }}-status"
                            />
                        </div>
                    </div>
                </div>
            @endif


            @if($columns['milestone'])
                <div class="col-span-1 {{ $lastColumn === 'milestone' ? 'text-right pr-4' : '' }}">
                    <div
                        x-data="{ open: false }"
                        x-on:milestoneupdated.window="if ($event.detail.taskId == {{ $task->id }}) open = false"
                        class="relative"
                    >
                        <!-- Display Current Milestone -->
                        <span
                            class="text-blue-500 hover:underline cursor-pointer"
                            @click="open = !open"
                        >
                {{ $task->milestone->name ?? 'N/A' }}
            </span>

                        <!-- Lazy-Loaded Dropdown -->
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition.opacity.duration.300ms
                            class="absolute mt-1 bg-white border shadow-lg rounded-md w-64 z-10"
                            style="display: none;"
                        >
                            <livewire:task-milestone-editable
                                lazy
                                :taskId="$task->id"
                                wire:key="task-{{ $task->id }}-milestone"
                            />
                        </div>
                    </div>
                </div>
            @endif


            @if($columns['due_date'])
                <div class="col-span-1 {{ $lastColumn === 'due_date' ? 'text-right pr-4' : '' }}">
                    <div
                        x-data="{ open: false }"
                        @duedateupdated.window = "open = false"
                        class="relative"
                    >
                        <!-- Display Current Due Date -->
                        <span
                            class="text-blue-500 hover:underline cursor-pointer"
                            @click="open = !open"
                        >
                {{ $task->due_date ? $task->due_date->format('d M Y') : 'No Due Date' }}
            </span>

                        <!-- Lazy-Loaded Dropdown -->
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition.opacity.duration.300ms
                            class="absolute mt-1 bg-white border shadow-lg rounded-md w-64 z-10"
                        >
                            <livewire:task-due-date-editable
                                lazy
                                :taskId="$task->id"
                                wire:key="task-{{ $task->id }}-due-date"
                            />
                        </div>
                    </div>
                </div>
            @endif


    </div>
</div>
