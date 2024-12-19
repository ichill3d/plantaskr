<div class="relative h-full overflow-y-auto max-h-[calc(100vh-10rem)]">

    @php
        $enabledColumns = collect($columns)->filter(fn($isEnabled) => $isEnabled); // Filter enabled columns
        $lastColumn = $enabledColumns->keys()->last(); // Get the last enabled column key
        $enabledColumnsCount = collect($columns)->filter(fn($isEnabled) => $isEnabled)->count(); // Count enabled columns

    @endphp
    <!-- Header -->
    <div class="sticky top-0 z-10 bg-white shadow text-sm font-semibold text-gray-600  border-b pb-2">
        <div class="flex flex-row gap-4 w-full justify-between  px-4 py-2 "
             style="grid-template-columns: repeat({{ $enabledColumnsCount }}, minmax(0, 1fr));">
            <div class="flex items-start justify-start gap-2" >
                <div class=" text-left cursor-pointer" wire:click="sortBy('name')">
                    Task Name
                    @if($sortColumn === 'name')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </div>
                <div class="relative inline-block ml-2"  x-data="{ open: false, tempColumns: @js($columns) }">
                    <button class="text-blue-500 hover:underline" @click="open = true">[Edit Columns]</button>

                    <!-- Dropdown -->
                    <div
                         x-show="open"
                         class="absolute mt-2 bg-white border shadow-lg rounded-md w-64 p-4 z-50"
                         @click.away="open = false"
                         style="display: none;">
                        <!-- Column Options -->
                        <h4 class="font-semibold text-gray-800 mb-2">Select Columns</h4>
                        <div class="space-y-2">
                            @foreach($columns as $key => $value)
                                @if($key !== 'task_name') <!-- Task Name should always be shown -->
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="tempColumns['{{ $key }}']">
                                    <span class="ml-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                </label>
                                @endif
                            @endforeach
                        </div>

                        <!-- Buttons -->
                        <div class="mt-4 flex justify-end space-x-2">
                            <button class="px-4 py-2 bg-gray-300 text-black rounded-md hover:bg-gray-400"
                                    @click="open = false; tempColumns = @js($columns)">
                                Cancel
                            </button>
                            <button class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                                    @click="$wire.updateTaskColumns(tempColumns); open = false;">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right pr-4 cursor-pointer" wire:click="sortBy('project.name')">
                Project
                @if($sortColumn === 'project.name')
                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                @endif
            </div>
        </div>
        <div class="grid gap-4 text-sm text-gray-600 px-4 py-2"
             style="grid-template-columns: repeat({{ $enabledColumnsCount }}, minmax(0, 1fr));">

            @if($columns['priority'])
                <div class="col-span-1 text-left cursor-pointer {{ $lastColumn === 'priority' ? 'text-right pr-4' : '' }}" wire:click="sortBy('priority.name')">
                    Priority
                    @if($sortColumn === 'priority.name')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </div>
            @endif
            @if($columns['created_time'])
                <div class="col-span-1 text-left cursor-pointer {{ $lastColumn === 'created_time' ? 'text-right pr-4' : '' }}" wire:click="sortBy('created_at')">
                    Created Time
                    @if($sortColumn === 'created_at')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </div>
            @endif
            @if($columns['created_by'])
                <div class="col-span-1 text-left cursor-pointer  {{ $lastColumn === 'created_by' ? 'text-right pr-4' : '' }}" wire:click="sortBy('u.name')">
                    Created By
                    @if($sortColumn === 'u.name')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </div>
            @endif
            @if($columns['assigned_users'])
                <div class="col-span-1 text-left {{ $lastColumn === 'assigned_users' ? 'text-right pr-4' : '' }}">Assigned Users</div>
            @endif
            @if($columns['status'])
                <div class="col-span-1 text-left cursor-pointer {{ $lastColumn === 'status' ? 'text-right pr-4' : '' }}" wire:click="sortBy('task_status_id')">
                    Status
                    @if($sortColumn === 'task_status_id')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </div>
            @endif
            @if($columns['milestone'])
                <div class="col-span-1 text-left cursor-pointer {{ $lastColumn === 'milestone' ? 'text-right pr-4' : '' }}" wire:click="sortBy('m.name')">
                    Milestone
                    @if($sortColumn === 'm.name')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </div>
            @endif
            @if($columns['due_date'])
                <div class="col-span-1 cursor-pointer   {{ $lastColumn === 'due_date' ? 'text-right pr-4' : '' }}" wire:click="sortBy('due_date')">
                    Due Date
                    @if($sortColumn === 'due_date')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="sticky top-20 z-10 p-2 px-4 bg-white shadow-md border-b">
        <div class="flex space-x-8 items-start">
            <!-- Status Filter -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Status</h3>
                    <button
                        @click="$wire.set('selectedStatuses', [])"
                        class="text-blue-500 text-xs hover:underline"
                    >
                        [Clear]
                    </button>
                </div>

                <div class="space-y-2">
                    @foreach($statuses as $status)
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                wire:model.live="selectedStatuses"
                                value="{{ $status->id }}"
                                class="rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                            >
                            <span class="text-sm">{{ $status->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Priority Filter -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Priority</h3>
                    <button
                        @click="$wire.set('selectedPriorities', [])"
                        class="text-blue-500 text-xs hover:underline"
                    >
                        [Clear]
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach($priorities as $priority)
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                wire:model.live="selectedPriorities"
                                value="{{ $priority->id }}"
                                class="rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                            >
                            <span class="text-sm">{{ $priority->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Assigned Users Filter -->
            <div x-data="{ userSearch: '', filteredUsers: @js($users) }">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold text-gray-700">Assigned Users</h3>
                    <button
                        @click="userSearch = ''; $wire.set('selectedUsers', [])"
                        class="text-blue-500 text-xs hover:underline"
                    >
                        [Clear]
                    </button>
                </div>
                <input
                    type="text"
                    placeholder="Search users..."
                    x-model="userSearch"
                    class="w-full mb-2 text-sm border rounded-md focus:ring focus:ring-blue-500"
                >
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    <template x-for="user in filteredUsers.filter(u => u.name.toLowerCase().includes(userSearch.toLowerCase()))" :key="user.id">
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                :value="user.id"
                                wire:model.live="selectedUsers"
                                class="rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                            >
                            <span class="text-sm" x-text="user.name"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Project Filter -->
            <div x-data="{ projectSearch: '', filteredProjects: @js($projects) }">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold text-gray-700">Projects</h3>
                    <button
                        @click="projectSearch = ''; $wire.set('selectedProjects', [])"
                        class="text-blue-500 text-xs hover:underline"
                    >
                        [Clear]
                    </button>
                </div>
                <input
                    type="text"
                    placeholder="Search projects..."
                    x-model="projectSearch"
                    class="w-full mb-2 text-sm border rounded-md focus:ring focus:ring-blue-500"
                >
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    <template x-for="project in filteredProjects.filter(p => p.name.toLowerCase().includes(projectSearch.toLowerCase()))" :key="project.id">
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                :value="project.id"
                                wire:model.live="selectedProjects"
                                class="rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                            >
                            <span class="text-sm" x-text="project.name"></span>
                        </label>
                    </template>
                </div>
            </div>
        </div>
    </div>





    <!-- Task List -->
    <div class="space-y-4 mt-4">
        @forelse($tasks as $task)
            <div class="border rounded-md bg-white shadow-md hover:bg-gray-50 hover:text-blue-600 text-gray-800 ">
                <!-- Task Name and Project -->
                <a
                    href="{{ route('organizations.tasks.show', ['id'=>$task->team->id, 'organization_alias'=>$task->team->alias, 'task_id'=>$task->id]) }}"
                    class="flex justify-between items-start rounded-t-md  border-b  hover:bg-gray-100
                    border-t-4" style="border-top-color: {{ $task->project->color }};">
                    <div class="font-bold  px-4 py-2 ">{{ $task->name }}</div>
                    <div class="rounded-bl-full px-4 pb-1 pl-6"
                         style="background-color: {{ $task->project->color }}; color: {{ get_contrast_color($task->project->color) }}">
                        {{ $task->project->name }}
                    </div>

                </a>

                <!-- Task Details -->
                <div class="grid gap-4 text-sm text-gray-600 px-4 py-2"
                     style="grid-template-columns: repeat({{ $enabledColumnsCount }}, minmax(0, 1fr));">
                    @if($columns['priority'])
                        <div class="col-span-1 {{ $lastColumn === 'priority' ? 'text-right pr-4' : '' }}">
                            <span class="px-2 py-1 text-white rounded-md"
                                  style="background-color: {{ $task->priority_color ?? '#ccc' }}">
                                {{ $task->priority->name ?? 'N/A' }}
                            </span>
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
                            {{ $task->assignees->pluck('name')->join(', ') }}
                        </div>
                    @endif
                    @if($columns['status'])
                        <div class="col-span-1 {{ $lastColumn === 'status' ? 'text-right pr-4' : '' }}">
                            {{ $task->status->name ?? 'N/A' }}
                        </div>
                    @endif
                    @if($columns['milestone'])
                        <div class="col-span-1 {{ $lastColumn === 'milestone' ? 'text-right pr-4' : '' }}">
                            {{ $task->milestone->name ?? 'N/A' }}
                        </div>
                    @endif
                    @if($columns['due_date'])
                        <div class="col-span-1 {{ $lastColumn === 'due_date' ? 'text-right pr-4' : '' }}">
                            {{ $task->due_date ? $task->due_date->format('d M Y') : 'N/A' }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">
                No tasks found for this team.
            </div>
        @endforelse
    </div>
</div>
