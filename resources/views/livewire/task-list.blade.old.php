<div>

    <div class="flex items-center justify-start space-x-4 p-4">

        <!-- Project Filter -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="px-4 py-2 bg-gray-200 rounded">
                Projects <span>&#9662;</span>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 bg-white border rounded shadow-lg z-10">
                <div class="max-h-40 overflow-y-auto">
                    @foreach ($projects as $project)
                        <label class="flex items-center space-x-2 px-2 py-1">
                            <input
                                type="checkbox"
                                value="{{ $project->id }}"
                                wire:click="toggleProject({{ $project->id }})"
                                @if (in_array($project->id, $selectedProjects)) checked @endif
                                class="form-checkbox h-4 w-4 text-blue-600"
                            >
                            <span>{{ $project->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Priority Filter -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="px-4 py-2 bg-gray-200 rounded">
                Priority <span>&#9662;</span>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 bg-white border rounded shadow-lg z-10">
                <div class="max-h-40 overflow-y-auto">
                    @foreach ($priorities as $priority)
                        <label class="flex items-center space-x-2 px-2 py-1">
                            <input
                                type="checkbox"
                                value="{{ $priority->id }}"
                                wire:click="togglePriority({{ $priority->id }})"
                                @if (in_array($priority->id, $selectedPriorities)) checked @endif
                                class="form-checkbox h-4 w-4 text-blue-600"
                            >
                            <span>{{ ucfirst($priority->name) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Assignee Filter -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="px-4 py-2 bg-gray-200 rounded">
                Assignees <span>&#9662;</span>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 bg-white border rounded shadow-lg z-10">
                <div class="max-h-40 overflow-y-auto">
                    @foreach ($assignees as $assignee)
                        <label class="flex items-center space-x-2 px-2 py-1">
                            <input
                                type="checkbox"
                                value="{{ $assignee->id }}"
                                wire:click="toggleAssignee({{ $assignee->id }})"
                                @if (in_array($assignee->id, $selectedAssignees)) checked @endif
                                class="form-checkbox h-4 w-4 text-blue-600"
                            >
                            <span>{{ $assignee->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Status Filter -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="px-4 py-2 bg-gray-200 rounded">
                Status <span>&#9662;</span>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 bg-white border rounded shadow-lg z-10">
                <div class="max-h-40 overflow-y-auto">
                    @foreach ($statuses as $status)
                        <label class="flex items-center space-x-2 px-2 py-1">
                            <input
                                type="checkbox"
                                value="{{ $status->id }}"
                                wire:click="toggleStatus({{ $status->id }})"
                                @if (in_array($status->id, $selectedStatuses)) checked @endif
                                class="form-checkbox h-4 w-4 text-blue-600"
                            >
                            <span>{{ $status->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Milestone Filter (Conditional Display) -->
        @if (!empty($selectedProjects))
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-4 py-2 bg-gray-200 rounded">
                    Milestones <span>&#9662;</span>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 bg-white border rounded shadow-lg z-10">
                    <div class="max-h-40 overflow-y-auto">
                        @foreach ($milestones as $milestone)
                            <label class="flex items-center space-x-2 px-2 py-1">
                                <input
                                    type="checkbox"
                                    value="{{ $milestone->id }}"
                                    wire:click="toggleMilestone({{ $milestone->id }})"
                                    @if (in_array($milestone->id, $selectedMilestones)) checked @endif
                                    class="form-checkbox h-4 w-4 text-blue-600"
                                >
                                <span>{{ $milestone->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>



@if ($tasks->isNotEmpty())




    <!-- Table Header -->
    <div class="hidden md:flex items-center justify-between px-4 py-2 font-semibold text-gray-600 text-sm uppercase tracking-wide">
        <div class="w-1/4 truncate cursor-pointer" wire:click="sortBy('name')">
            Task
            @if ($sortColumn === 'name')
                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
            @endif
        </div>
        <div class="w-1/6 truncate cursor-pointer" wire:click="sortBy('task_priorities_id')">
            Priority
            @if ($sortColumn === 'task_priorities_id')
                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
            @endif
        </div>
        <div class="w-1/6 truncate cursor-pointer" wire:click="sortBy('due_date')">
            Due
            @if ($sortColumn === 'due_date')
                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
            @endif
        </div>
        <div class="w-1/6 truncate cursor-pointer" wire:click="sortBy('project_id')">
            Project
            @if ($sortColumn === 'project_id')
                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
            @endif
        </div>
        <div class="w-1/4 truncate">Assignees</div>
        <div class="w-1/6 truncate cursor-pointer" wire:click="sortBy('task_status_id')">Status
            @if ($sortColumn === 'task_status_id')
                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
            @endif</div>
    </div>

    <!-- Task Rows -->
    @foreach ($tasks as $task)
        <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 border-t border-gray-200 rounded-md mb-1"
             style="border-left: 7px solid {{ $task->project->color ?? '#ddd' }};">
            <!-- Task Name and Description -->
            <div class="w-1/4">
                <h3 class="text-sm font-medium text-gray-800 truncate">

                    <a href="{{ route('organizations.tasks.show', ['id'=>$task->team->id, 'organization_alias'=>$task->team->alias, 'task_id'=>$task->id]) }}" class="hover:underline text-blue-600">
                        {{ $task->name }}
                    </a>
                </h3>
                <p class="text-xs text-gray-600 truncate">{{ $task->description }}</p>
            </div>

            <!-- Priority -->
            <div class="w-1/6">
                <span class="px-2 py-1 rounded-full text-white text-xs font-semibold bg-{{ $task->priority_color }}">
                    {{ ucfirst($task->priority->name ?? 'Low') }}
                </span>
            </div>

            <!-- Due Date -->
            <div class="w-1/6">
                <span class="{{ $task->is_overdue ? 'text-red-500' : 'text-gray-700' }} text-sm">
                    {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'No Deadline' }}
                </span>
            </div>

            <!-- Project -->
            <div class="w-1/6 text-sm text-gray-600 truncate">
                {{ $task->project->name ?? 'No Project' }}
            </div>

            <!-- Assignees -->
            <div class="w-1/4 flex space-x-2">
                @foreach ($task->assignees as $assignee)
                    <div class="relative w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center">
                        @if ($assignee->profile_photo_url)
                            <img src="{{ $assignee->profile_photo_url }}" alt="{{ $assignee->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-sm font-semibold text-gray-700">
                                {{ strtoupper(substr($assignee->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Status -->
            <div class="w-1/6 text-sm text-gray-600 truncate">
                {{ $task->status->name }}
            </div>

            <div class="w-1/12 flex justify-end">
                <button
                    @click="if (document.querySelector('[x-data]')) {
            $dispatch('usermessage-show', {
                type: 'confirm',
                title: 'Confirm Task Deletion',
                message: 'Are you sure you want to delete this task?',
                action: () => { @this.call('deleteTask', {{ $task->id }}) }
            });
        }"
                    class="bg-red-300 text-white p-2 rounded-full hover:bg-red-600 transition"
                    aria-label="Delete Task"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    @endforeach

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
    @else
        <div>No Tasks Here</div>
    @endif
</div>
