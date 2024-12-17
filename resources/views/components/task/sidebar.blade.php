<div class="bg-gray-100 shadow rounded-lg p-6 space-y-4">
    <!-- Status Section -->
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Status</h3>
        <div x-data="{ open: false }" class="relative">
            <!-- Current Status Link -->
            <a href="#" @click.prevent="open = !open" class="text-blue-600 hover:underline">
                {{ $task->status->name }}
            </a>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" class="absolute bg-white shadow-md rounded-md mt-1 w-48 z-10">
                @foreach ($statuses as $status)
                    <a href="#" wire:click.prevent="changeStatus({{ $status->id }})"  @click="open = false"
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                        {{ $status->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Metadata -->
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Metadata</h3>
        <ul class="space-y-2 text-sm text-gray-600">
            <li>
                <div class="font-semibold">Date Created:</div>
                <span>{{ $task->created_at }}</span>
            </li>
            <li>
                <div class="font-semibold">Project:</div>
                <span><a  class="text-blue-600 hover:underline"  href="{{route('organizations.projects.show',
                                        ['id'=>$task->team->id,
                                        'organization_alias' => $task->team->alias,
                                        'project_id' => $task->project->id])
                                        }}">{{ $task->project->name }}</a></span>
            </li>
            <li>
                <!-- Milestone Section -->
                <div class="font-semibold">Milestone:</div>
                <div x-data="{ open: false }" class="relative">
                    @if ($task->milestone)
                        <!-- Current Milestone as a Clickable Link -->
                        <a href="#" @click.prevent="open = !open" class="text-blue-600 hover:underline">
                            {{ $task->milestone->name }}
                        </a>
                    @else
                        <!-- Add to Milestone Link -->
                        <a href="#" @click.prevent="open = !open" class="text-blue-600 hover:underline">
                            [add to milestone]
                        </a>
                    @endif

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" class="absolute bg-white shadow-md rounded-md mt-1 w-48 z-10">
                        @foreach ($milestones as $milestone)
                            <a href="#" wire:click.prevent="assignMilestone({{ $milestone->id }})"
                               @click="open = false"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                                {{ $milestone->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </li>
            <li>
                <div x-data="{ open: false }" class="relative">
                    <div class="font-semibold flex items-center">
                        Assignees:
                        <button @click.prevent="open = !open" class="ml-2 text-gray-500 hover:text-gray-700">
                            <!-- Pencil Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 13V16H7L15.5 7.5L12.5 4.5L4 13Z" />
                                <path fill-rule="evenodd" d="M13.207 2.793a1 1 0 0 1 1.414 0l2.586 2.586a1 1 0 0 1 0 1.414l-8.5 8.5A1 1 0 0 1 8 16H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 .293-.707l8.5-8.5ZM15.5 5.914L14.086 4.5 4.5 14.086V15.5H5.914L15.5 5.914Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>


                    <!-- Dropdown with Checkboxes -->
                    <div x-show="open" @click.away="open = false" class="absolute top-full left-0 mt-2 bg-white shadow-md rounded-md w-64 z-10">
                        <div class="text-sm text-gray-700 px-4 py-2 font-semibold">Team Members:</div>
                        <ul class="max-h-64 overflow-auto divide-y">
                            @foreach ($teamMembers as $member)
                                <li class="px-4 py-2 flex items-center justify-between">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" wire:click="toggleAssignee({{ $member->id }})"
                                               @if ($task->assignees->contains($member)) checked @endif>
                                        <span>{{ $member->name }}</span>
                                    </label>
                                    <!-- Role Display -->
                                    @if ($task->assignees->contains($member))
                                        <span class="text-xs text-gray-500">(Assigned)</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Current Assignees Display -->
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        @foreach ($task->assignees as $assignee)
                            <div title="{{$assignee->name}}" class="relative w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center">
                                @if ($assignee->profile_photo_path)
                                    <img src="{{ asset('storage/' . $assignee->profile_photo_path) }}" alt="{{ $assignee->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-sm font-semibold text-gray-700">
                        {{ strtoupper(substr($assignee->name, 0, 1)) }}
                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>



            </li>
            <li>
                <div class="font-semibold">Priority:</div>
                <div x-data="{ open: false }" class="relative">
                    <!-- Current Priority Link -->
                    <a href="#" @click.prevent="open = !open" class="text-blue-600 hover:underline">
                        {{ $task->priority->name }}
                    </a>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" class="absolute bg-white shadow-md rounded-md mt-1 w-48 z-10">
                        @foreach ($priorities as $priority)
                            <a href="#" wire:click.prevent="changePriority({{ $priority->id }})" @click="open = false"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-200">
                                {{ $priority->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
