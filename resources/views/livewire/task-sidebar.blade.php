<div class="">
    <!-- Task Properties Section -->
    <div class="space-y-4 text-sm text-gray-600">
        <!-- status Section -->
        <div>
            <div class="font-semibold">Status:</div>
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
        <!-- Due Date Section -->
        <div>
            <div class="font-semibold">Due Date:</div>
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

        <!-- Project Section -->
        <div>
            <div class="font-semibold">Project:</div>
            <livewire:task-project-editable :taskId="$task->id" />
        </div>

        <!-- Milestone Section -->
        <div>
            <div class="font-semibold">Milestone:</div>
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
    </div>

    <!-- Other Sidebar Content -->
    <div class=" py-4 mt-4 border-t border-gray-200">
        <button
            @click="if (document.querySelector('[x-data]')) {
                $dispatch('usermessage-show', {
                    type: 'confirm',
                    title: 'Confirm Task Deletion',
                    message: 'Are you sure you want to delete this task?',
                    action: () => { @this.call('deleteTask') }
                });
            }"
            class=" bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-800 transition"
        >
            Archive Task
        </button>
    </div>
</div>
