<div class="relative h-full overflow-y-auto max-h-[calc(100vh-10rem)]">

    <!-- Header -->
    <div x-data="{ toggleAllFilters: false }" class="sticky top-0 z-10 bg-white shadow text-sm font-semibold text-gray-600  border-b pb-2">
        <div  class="flex flex-row gap-4 w-full justify-between  px-4 py-2 "
             style="grid-template-columns: repeat({{ $enabledColumnsCount }}, minmax(0, 1fr));">
            <div class="flex items-start justify-start gap-2" >
                <div class=" text-left cursor-pointer" wire:click="sortBy('name')">
                    Task Name
                    @if($sortColumn === 'name')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                </div>
                <div class="relative inline-block ml-2"  x-data="{ open: false, tempColumns: @js($columns) }">
                    <button class="text-blue-500 hover:underline" @click="open = true">Edit Columns</button>
                    <button
                        class="text-blue-500 hover:underline ml-2"
                        wire:click="clearAllFilters"
                    >
                        Clear All Filters
                    </button>
                    <button @click="toggleAllFilters = ! toggleAllFilters" class="hidden text-blue-500 hover:underline ml-2">
                        Toggle Filters
                    </button>
                    <!-- Dropdown -->
                    <div
                         x-show="open"
                         class="absolute mt-2 bg-white border shadow-lg rounded-md w-64 p-4"
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

            <div x-data="{ showFilter: false, searchQuery: '' }" class="text-right pr-4 cursor-pointer">
                <div class="flex flex-row items-center justify-end">
                    <!-- Selected Project Filters -->
                    <div>
                        @foreach($selectedProjects as $projectId)
                            @php
                                $project = $projects->firstWhere('id', $projectId);
                            @endphp
                            <span class="inline-block mt-1 bg-gray-500 font-normal rounded-full px-2 py-0.5 text-xs text-white whitespace-nowrap">
                    <span class="inline-block max-w-20 overflow-hidden align-middle">{{ $project->name }}</span>
                    <span
                        class="inline-block ml-2 cursor-pointer hover:text-blue-600 align-middle"
                        @click="$refs['project_{{ $projectId }}']?.click()"
                    >
                        X
                    </span>
                </span>
                        @endforeach
                    </div>

                    <div>
            <span @click="showFilter = !showFilter" class="inline-block ml-1 cursor-pointer hover:bg-gray-100 rounded-full p-1 -mb-1.5">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="w-3 h-3"
                >
                    <!-- SVG Path -->
                    <path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z" clip-rule="evenodd" />
                </svg>
            </span>
                        <span wire:click="sortBy('project.name')">Project</span>
                        @if($sortColumn === 'project.name')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif

                        <!-- Project Filter Dropdown -->
                        <div x-show="showFilter" class="absolute -ml-44 mt-2 bg-white border shadow-lg rounded-md w-64 p-4">
                            <div class="flex justify-between items-center mb-2">
                                <button @click="$wire.set('selectedProjects', [])" class="text-blue-500 text-xs hover:underline">[Clear]</button>
                                <button @click="showFilter = false" class="p-1 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600">OK</button>
                            </div>

                            <!-- Search Input -->
                            <input
                                type="text"
                                x-model="searchQuery"
                                placeholder="Search projects..."
                                class="w-full mb-2 p-1 border rounded-md text-sm focus:ring focus:ring-blue-500"
                            >

                            <!-- Project List -->
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach($projects as $project)
                                    <template x-if="searchQuery === '' || '{{ strtolower($project->name) }}'.includes(searchQuery.toLowerCase())">
                                        <label class="flex items-center space-x-2">
                                            <input
                                                type="checkbox"
                                                wire:model.live="selectedProjects"
                                                value="{{ $project->id }}"
                                                x-ref="project_{{ $project->id }}"
                                                class="rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                                            >
                                            <span class="text-sm">{{ $project->name }}</span>
                                        </label>
                                    </template>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div   class="grid gap-4 text-sm text-gray-600 px-4 py-2"
             style="grid-template-columns: repeat({{ $enabledColumnsCount }}, minmax(0, 1fr));">

            @if($columns['priority'])
                <div x-data="{ showFilter: false }" class="col-span-1 text-left {{ $lastColumn === 'priority' ? 'text-right pr-4' : '' }}" >
                    <span class="cursor-pointer" wire:click="sortBy('priority.name')">Priority</span>
                    @if($sortColumn === 'priority.name')
                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                    @endif
                    <span @click='showFilter = !showFilter' class="inline-block ml-1 cursor-pointer hover:bg-gray-100 rounded-full p-1 -mb-1.5">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="w-3 h-3"
                        >
                            <path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z" clip-rule="evenodd" />
                        </svg>
                    </span>

                    <!-- Priority Filter -->
                    <div x-show="showFilter" class="absolute mt-2 bg-white border shadow-lg rounded-md w-64 p-4">
                        <div class="flex justify-between items-center mb-2">

                            <button
                                @click="$wire.set('selectedPriorities', [])"
                                class="text-blue-500 text-xs hover:underline" >[Clear]</button>
                            <button
                                @click="showFilter = false"
                                class="p-1 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600">OK</button>
                        </div>
                        <div class="space-y-2">
                            @foreach($priorities as $priority)
                                <label class="flex items-center space-x-2">
                                    <input
                                        type="checkbox"
                                        wire:model.live="selectedPriorities"
                                        x-ref="priority_{{ $priority->id }}"
                                        value="{{ $priority->id }}"
                                        class="rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                                    >
                                    <span class="text-sm">{{ $priority->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <!-- Selected Filter Items -->
                    <div>
                        @foreach($selectedPriorities as $priorityId)
                            @php
                                $priority = $priorities->firstWhere('id', $priorityId);
                            @endphp
                            <span class="inline-block mt-1 bg-gray-500 font-normal rounded-full px-2 py-0.5 text-xs text-white whitespace-nowrap">
                                <span class="inline-block max-w-20 overflow-hidden align-middle">{{ $priority->name }}</span>
                                <span
                                    class="inline-block ml-2 cursor-pointer hover:text-red-600 align-middle"
                                    @click="$refs['priority_{{ $priorityId }}'].click()">X</span>
                            </span>

                        @endforeach
                    </div>
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
                    <div x-data="{ showFilter: false, searchQuery: '' }" class="col-span-1 text-left {{ $lastColumn === 'assigned_users' ? 'text-right pr-4' : '' }}">
                        <span class="cursor-pointer" @click="showFilter = !showFilter">Assigned Users</span>
                        <span @click="showFilter = !showFilter" class="inline-block ml-1 cursor-pointer hover:bg-gray-100 rounded-full p-1 -mb-1.5">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="w-3 h-3"
            >
                <path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z" clip-rule="evenodd" />
            </svg>
        </span>

                        <!-- Assigned Users Filter Dropdown -->
                        <div x-show="showFilter" class="absolute mt-2 bg-white border shadow-lg rounded-md w-64 p-4">
                            <div class="flex justify-between items-center mb-2">
                                <button @click="$wire.set('selectedUsers', [])" class="text-blue-500 text-xs hover:underline">[Clear]</button>
                                <button @click="showFilter = false" class="p-1 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600">OK</button>
                            </div>

                            <!-- Search Input -->
                            <input
                                type="text"
                                x-model="searchQuery"
                                placeholder="Search Assignees..."
                                class="w-full mb-2 p-1 border rounded-md text-sm focus:ring focus:ring-blue-500"
                            >

                            <!-- User List -->
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach($users as $user)
                                    <template x-if="searchQuery === '' || '{{ strtolower($user->name) }}'.includes(searchQuery.toLowerCase())">
                                        <label class="flex items-center space-x-2">
                                            <input
                                                type="checkbox"
                                                wire:model.live="selectedUsers"
                                                value="{{ $user->id }}"
                                                x-ref="user_{{ $user->id }}"
                                                class="rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                                            >
                                            <span class="text-sm">{{ $user->name }}</span>
                                        </label>
                                    </template>
                                @endforeach
                            </div>
                        </div>

                        <!-- Selected User Filters -->
                        <div>
                            @foreach($selectedUsers as $userId)
                                @php
                                    $user = $users->firstWhere('id', $userId);
                                @endphp
                                <span class="inline-block mt-1 bg-gray-500 font-normal rounded-full px-2 py-0.5 text-xs text-white whitespace-nowrap">
                    <span class="inline-block max-w-20 overflow-hidden align-middle">
                        {{ collect(explode(' ', $user->name))->map(fn($name, $index) => $index === 0 ? $name : substr($name, 0, 1) . '.')->implode(' ') }}
                    </span>
                    <span
                        class="inline-block ml-2 cursor-pointer hover:text-blue-600 align-middle"
                        @click="$refs['user_{{ $userId }}'].click();"
                    >
    X
</span>
                </span>
                            @endforeach
                        </div>
                    </div>
                @endif


            @if($columns['status'])
                    <div x-data="{ showFilter: false }" class="col-span-1 text-left {{ $lastColumn === 'status' ? 'text-right pr-4' : '' }}">
                        <span class="cursor-pointer" wire:click="sortBy('task_status_id')">Status</span>
                        @if($sortColumn === 'task_status_id')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                        <span @click="showFilter = !showFilter" class="inline-block ml-1 cursor-pointer hover:bg-gray-100 rounded-full p-1 -mb-1.5">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="w-3 h-3"
                            >
                                <path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z" clip-rule="evenodd" />
                            </svg>
                        </span>

                        <!-- Status Filter Dropdown -->
                        <div x-show="showFilter" class="absolute mt-2 bg-white border shadow-lg rounded-md w-64 p-4">
                            <div class="flex justify-between items-center mb-2">
                                <button @click="$wire.set('selectedStatuses', [])" class="text-blue-500 text-xs hover:underline">[Clear]</button>
                                <button @click="showFilter = false" class="p-1 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600">OK</button>
                            </div>
                            <div class="space-y-2">
                                @foreach($statuses as $status)
                                    <label class="flex items-center space-x-2">
                                        <input
                                            type="checkbox"
                                            wire:model.live="selectedStatuses"
                                            value="{{ $status->id }}"
                                            x-ref="status_{{ $status->id }}"
                                            class="rounded border-gray-300 text-blue-500 focus:ring focus:ring-blue-500"
                                        >
                                        <span class="text-sm">{{ $status->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Selected Status Filters -->
                        <div>
                            @foreach($selectedStatuses as $statusId)
                                @php
                                    $status = $statuses->firstWhere('id', $statusId);
                                @endphp
                                <span class="inline-block mt-1 bg-gray-500 font-normal rounded-full px-2 py-0.5 text-xs text-white whitespace-nowrap">
                    <span class="inline-block max-w-20 overflow-hidden  align-middle">{{ $status->name }}</span>
                    <span
                        class="inline-block ml-2 cursor-pointer hover:text-blue-600  align-middle"
                        @click="$refs['status_{{ $statusId }}'].click()">X</span>
                </span>
                            @endforeach
                        </div>
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

        <div class="flex flex-col hidden">

            <div x-show="toggleAllFilters" class=" p-2 px-4 bg-white shadow-md border-b">
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
                                    x-ref="priority_{{ $priority->id }}"
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
        </div>

    </div>







    <!-- Task List -->
    <div class="space-y-4 mt-4">
        @forelse($tasks as $task)
            <div class="border rounded-md bg-white shadow-md hover:bg-gray-50 hover:text-blue-600 text-gray-800 ">
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
                         style="background-color: {{ $task->project->color }}; color: {{ get_contrast_color($task->project->color) }}">
                        {{ $task->project->name }}
                    </div>
                </a>



                <!-- Task Details -->
                <div class="grid gap-4 text-sm text-gray-600 px-4 py-2"
                     style="grid-template-columns: repeat({{ $enabledColumnsCount }}, minmax(0, 1fr));">
                    @if($columns['priority'])
                        <div class="col-span-1 {{ $lastColumn === 'priority' ? 'text-right pr-4' : '' }}">
                            <livewire:task-priority-editable :task="$task" />
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
                                <livewire:task-assignees-editable :task="$task" />
                            </div>
                        @endif



                    @if($columns['status'])
                            <div class="col-span-1 {{ $lastColumn === 'status' ? 'text-right pr-4' : '' }}">
                                <!-- Livewire Task Status Component -->
                                <livewire:task-status-editable :task="$task" />
                            </div>
                        @endif

                        @if($columns['milestone'])
                            <div class="col-span-1 {{ $lastColumn === 'milestone' ? 'text-right pr-4' : '' }}">
                                <livewire:task-milestone-editable :task="$task" />
                            </div>
                        @endif

                        @if($columns['due_date'])
                            <div class="col-span-1 {{ $lastColumn === 'due_date' ? 'text-right pr-4' : '' }}">
                                <livewire:task-due-date-editable :task="$task" />
                            </div>
                        @endif

                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">
                No tasks found.
            </div>
        @endforelse
    </div>
    <!-- Task Modal Livewire Component -->

    <livewire:task-modal :taskId="$selectedTask?->id" />

</div>

@script
<script>
    window.addEventListener('popstate', () => {
        const params = new URLSearchParams(window.location.search);

        // Parse Array Parameters (Helper Function)
        const parseArrayParams = (keyPrefix) => {
            const results = [];
            for (const [key, value] of params.entries()) {
                if (key.startsWith(keyPrefix)) {
                    results.push(value);
                }
            }
            return results;
        };

        // Parse Filters
        const filters = {
            selectedUsers: parseArrayParams('selectedUsers'),
            selectedProjects: parseArrayParams('selectedProjects'),
            selectedStatuses: JSON.parse(params.get('selectedStatuses') || '[]'),
            selectedPriorities: JSON.parse(params.get('selectedPriorities') || '[]'),
            sortColumn: params.get('sortColumn') || 'name',
            sortDirection: params.get('sortDirection') || 'asc',
        };

        // Dispatch Filter Update to Livewire Task List Component
        Livewire.dispatch('filterUpdated', JSON.parse(JSON.stringify(filters)));

        // Handle Task Modal Based on URL
        const pathSegments = window.location.pathname.split('/').filter(Boolean);
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


