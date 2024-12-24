<div class="">
    <!-- Task Properties Section -->
    <div class="space-y-4 text-sm text-gray-600">
        <!-- status Section -->
        <div>
            <div class="font-semibold">Status:</div>
            <livewire:task-status-editable :taskId="$task->id" />
        </div>
        <!-- Due Date Section -->
        <div>
            <div class="font-semibold">Due Date:</div>
            <livewire:task-due-date-editable :taskId="$task->id" />
        </div>

        <!-- Project Section -->
        <div>
            <div class="font-semibold">Project:</div>
            <livewire:task-project-editable :taskId="$task->id" />
        </div>

        <!-- Milestone Section -->
        <div>
            <div class="font-semibold">Milestone:</div>
            <livewire:task-milestone-editable :taskId="$task->id" />
        </div>

        <!-- Assigned Users Section -->
        <div>
            <div class="font-semibold">Assigned Users:</div>
            <livewire:task-assignees-editable :taskId="$task->id" />
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
