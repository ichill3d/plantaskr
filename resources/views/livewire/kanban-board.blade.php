<div class="relative h-full overflow-y-hidden max-h-[calc(100vh-10rem)]">
    <!-- Status Header -->
    <div class="grid sticky top-0 z-30 grid-cols-4 gap-4 overflow-x-auto">
        @foreach ($statuses as $statusId => $statusName)
            <div class="text-lg font-bold p-2 bg-white shadow-md">
                {{ $statusName }}
            </div>
        @endforeach
    </div>

    <!-- Task Board -->
    <div
        class="grid grid-cols-4 gap-4 overflow-x-auto h-full"
        x-data="{
            draggingTask: null,
            draggingStatus: null,
            placeholderIndex: null,
            placeholderStatus: null
        }"
    >
        @foreach ($statuses as $statusId => $statusName)
            <div
                class="bg-gray-100 rounded-lg shadow-md h-full overflow-y-auto relative flex flex-col  scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
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
                            class="bg-white rounded-xl border-2 border-t-0 border-gray-300
                            shadow cursor-move text-sm xl:text-base transition-transform duration-300 ease-in-out
                            "
                            style="border-top-color: {{ $task['project']['color'] }};"
                            draggable="true"
                            @dragstart="
                                draggingTask = {{ $task['id'] }};
                                draggingStatus = {{ $task['task_status_id'] }};
                            "
                            :class="{
                                'opacity-50 scale-95': draggingTask === {{ $task['id'] }},
                            }"
                            x-data="{ showModal: false, taskLoaded: false }"
                        >
                            <div class="overflow-y-hidden border-t-2 border-t-gray-400 h-6 text-xs text-center rounded-t-xl p-1 opacity-50"
                                 style="background-color: {{$task['project']['color']}}; color: {{ get_contrast_color($task['project']['color']) }} ">
                                <span class="opacity-80">{{ $task['project']['name'] }}</span>
                            </div>
                            <!-- Task Name -->
                            <div class="rounded-xl p-2 border-b bg-gray-50 font-semibold mb-2">
                                {{ $task['name'] }}
                            </div>
                            <div class="p-2">

                            <!-- Assignees -->
                            <div class="border-b border-gray-300 pb-2 mb-2 flex items-center justify-between">
                                <div>Assignees:</div>
                                <div class="flex -space-x-2">
                                    @foreach ($task['assignees'] as $assignee)
                                        <img
                                            title="{{ $assignee['name'] }}"
                                            src="{{ $assignee['profile_photo_url'] }}"
                                            class="object-cover w-8 h-8 rounded-full border-2 border-white"
                                        />
                                    @endforeach
                                </div>
                            </div>

                            <!-- Priority -->
                            <div class="border-b border-gray-300 pb-2 mb-2 flex items-center justify-between">
                                <div>Priority:</div>
                                <span
                                    class="px-2 py-1 rounded-md text-white"
                                    style="background-color: {{ $task['priority_color'] ?? '#ccc' }};"
                                >
                                    {{ $task['priority']['name'] ?? 'No Priority' }}
                                </span>
{{--                                @php--}}
{{--                                    $taskModel = \App\Models\Task::find($task['id'] ?? null);--}}
{{--                                @endphp--}}
{{--                                <div>Priority:</div>--}}
{{--                                <livewire:task-priority-editable :task="$taskModel" />--}}

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
            console.log('Kanban Task Clicked: {{ $task['id'] }}');
            Livewire.dispatchTo('task-modal', 'openTaskModal', { taskId: {{ $task['id'] }} });
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('modal-opened'));
            }, 100);
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
    @script
    <script>
        window.addEventListener('popstate', () => {
        // Handle Task Modal Based on URL
        const pathSegments = window.location.pathname.split('/').filter(Boolean);
        console.log(pathSegments);
        const taskId = pathSegments[pathSegments.length - 1];

        if (!isNaN(taskId) && taskId !== '') {
            Livewire.dispatch( 'openTaskModal', { taskId: parseInt(taskId) });
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('modal-opened'));
            }, 100);
        } else {
            console.log("No Task ID detected, closing modal.");
            Livewire.dispatch('closeTaskModal');
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('modal-closed'));
            }, 100);
        }
    });
    </script>
    @endscript
</div>
