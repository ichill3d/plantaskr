<div class="">
    <!-- Task Properties Section -->
    <div class="space-y-4 text-sm text-gray-600">
        <!-- status Section -->
        <div>
            <div class="font-semibold">Status:</div>
            <livewire:task-status-editable :task="$task" />
        </div>
        <!-- Due Date Section -->
        <div>
            <div class="font-semibold">Due Date:</div>
            <livewire:task-due-date-editable :task="$task" />
        </div>

        <!-- Project Section -->
        <div>
            <div class="font-semibold">Project:</div>
            <livewire:task-project-editable :task="$task" />
        </div>

        <!-- Milestone Section -->
        <div>
            <div class="font-semibold">Milestone:</div>
            <livewire:task-milestone-editable :task="$task" />
        </div>

        <!-- Assigned Users Section -->
        <div>
            <div class="font-semibold">Assigned Users:</div>
            <livewire:task-assignees-editable :task="$task" />
        </div>

        <!-- Priority Section -->
        <div>
            <div class="font-semibold">Priority:</div>
            <livewire:task-priority-editable :task="$task" />
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
