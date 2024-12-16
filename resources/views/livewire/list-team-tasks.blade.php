<div class="bg-white shadow-md rounded-lg">
    <!-- Table Header -->
    <div class="hidden md:flex items-center justify-between px-4 py-2 font-semibold text-gray-600 text-sm uppercase tracking-wide">
        <div class="w-1/3 truncate cursor-pointer" wire:click="sortBy('name')">
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
    </div>

    <!-- Task Rows -->
    @foreach ($tasks as $task)
        <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 border-t border-gray-200 rounded-md mb-1"
             style="border-left: 7px solid {{ $task->project->color ?? '#ddd' }};">
            <!-- Task Name and Description -->
            <div class="w-1/3">
                <h3 class="text-sm font-medium text-gray-800 truncate">
                    <a href="{{ route('tasks.show', $task->id) }}" class="hover:underline text-blue-600">
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
        </div>
    @endforeach

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</div>

