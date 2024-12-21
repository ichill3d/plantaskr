<div class="grid grid-cols-4 gap-4" x-data="{ draggingTask: null }">
    @foreach ($statuses as $statusId => $statusName)
        <div
            class="bg-gray-100 p-1 rounded-lg shadow-md"
            @dragover.prevent
            @drop="
                Livewire.dispatch('updateTaskStatus', { taskId: draggingTask, statusId: {{ $statusId }} });
                draggingTask = null;
                ">
            <h2 class="text-lg font-bold p-2">{{ $statusName }}</h2>

            <!-- Task List -->
            <div class="space-y-1">
                @foreach ($tasks[$statusId] ?? [] as $task)
                    <div
                        class="bg-white rounded-xl border-2 border-t-[5px] border-gray-300 shadow cursor-move text-sm xl:text-base"
                        style="border-top-color: {{ $task['project']['color'] }};"
                        draggable="true"
                        @dragstart="draggingTask = {{ $task['id'] }}"
                    >

                        <!-- Task Name -->

                            <div class="rounded-xl p-2 border-b bg-gray-50 font-semibold mb-2">
                                {{ $task['name'] }}
                            </div>


                        <div class="p-2 pt-0">

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
                                <span class="px-2 py-1 rounded-md text-white" style="background-color: {{ $task['priority_color'] ?? '#ccc' }}">
                                    {{ $task['priority']['name'] ?? 'No Priority' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-center">
                                <a href="#" class="bg-gray-200 px-1 text-gray-400 rounded-lg hover:text-blue-600">Open Task</a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
