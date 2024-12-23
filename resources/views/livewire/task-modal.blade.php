<div>
    @if ($task)
        <div
            x-data="{ open: @entangle('task').defer !== null }"
            x-show="open"
            x-transition.opacity.duration.300ms
            @keydown.window.escape="open = false; Livewire.dispatch('closeTaskModal');"
            x-on:modal-closed.window="
            open = false;
            history.pushState({}, '', '{{ route('organizations.tasks', ['id' => $task->team->id, 'organization_alias' => $task->team->alias]) }}');
        "
            x-on:modal-opened="
            open = true;
        "
            class="fixed inset-0 z-50 flex items-center justify-center"
            style="display: none;">

            <!-- Modal Overlay -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>

            <!-- Modal Content -->
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl h-[90vh] flex flex-col overflow-hidden">

                    <!-- Modal Header -->
                    <div class="p-4 border-b flex justify-between items-center">
                        <h2 class="text-2xl font-bold">{{ $task->name ?? 'Task Details' }}</h2>
                        <button
                            @click="
                            Livewire.dispatch('closeTaskModal');
                            open = false;
                            history.pushState({}, '', '{{ route('organizations.tasks', ['id' => $task->team->id, 'organization_alias' => $task->team->alias]) }}');
                        "
                            class="text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>
                    <div class="px-4 h-full">
                        <livewire:show-task :taskId="$task->id" />
                    </div>


                </div>
            </div>
        </div>
    @endif
</div>
