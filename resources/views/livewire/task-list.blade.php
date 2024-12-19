<div class="relative h-full overflow-y-auto max-h-[calc(100vh-10rem)]">

    @php
        $enabledColumns = collect($columns)->filter(fn($isEnabled) => $isEnabled); // Filter enabled columns
        $lastColumn = $enabledColumns->keys()->last(); // Get the last enabled column key
        $enabledColumnsCount = collect($columns)->filter(fn($isEnabled) => $isEnabled)->count(); // Count enabled columns

    @endphp
    <!-- Header -->
    <div class="grid gap-4 text-sm font-semibold text-gray-600 border-b pb-2 bg-white sticky top-0 z-10 shadow"
         style="grid-template-columns: repeat({{ $enabledColumnsCount }}, minmax(0, 1fr));">
        <div class="col-span-4 flex items-start justify-start gap-2" >
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

        <div class="col-span-4 text-right pr-4 cursor-pointer" wire:click="sortBy('project.name')">
            Project
            @if($sortColumn === 'project.name')
                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
            @endif
        </div>


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

    <!-- Task List -->
    <div class="space-y-4 mt-4">
        @forelse($tasks as $task)
            <div class="border rounded-md bg-white shadow-md">
                <!-- Task Name and Project -->
                <div class="flex justify-between items-center px-4 py-2 border-b cursor-pointer hover:bg-gray-50">
                    <div class="font-bold text-gray-800">{{ $task->name }}</div>

                    <div class="text-sm text-gray-600">{{ $task->project->name }}</div>

                </div>

                <!-- Task Details -->
                <div class="grid gap-4 text-sm text-gray-600 px-4 py-2"
                     style="grid-template-columns: repeat({{ $enabledColumnsCount }}, minmax(0, 1fr));">
                    @if($columns['priority'])
                        <div class="col-span-1 {{ $lastColumn === 'priority' ? 'text-right pr-4' : '' }}">
                            <span class="px-2 py-1 text-white rounded-md"
                                  style="background-color: {{ $task->priority->color ?? '#ccc' }}">
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
