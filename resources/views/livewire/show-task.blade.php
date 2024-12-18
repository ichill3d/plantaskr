<div class="grid grid-cols-[2fr_1fr] gap-4">
    <!-- Main Content -->
    <div class="overflow-auto">
        <!-- Task Title -->
        <a href="{{route('organizations.projects.show',
                                        ['id'=>$task->team->id,
                                        'organization_alias' => $task->team->alias,
                                        'project_id' => $task->project->id])
                                        }}"
           style="background-color: {{ $task->project->color }}; color: {{ get_contrast_color($task->project->color) }}"
             class="block text-xs font-medium mr-2 px-3 py-2 rounded-md uppercase tracking-wide mb-2 hover:opacity-75">
            <span class="opacity-50">Project:</span> {{ $task->project->name }}
        </a>

        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $task->name }}</h1>

        <!-- Task Description -->
        <div class="text-gray-600 mb-6 border rounded-md p-4">
            <p>
                {!! $task->description !!}
            </p>
        </div>

        <!-- Files Section -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Files</h2>
            <livewire:file-upload :taskId="$task->id" />
        </div>

        <!-- Discussion Section -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Discussion</h2>
            <div class="border rounded-md p-4 bg-gray-50">
                <p class="text-gray-500">No comments yet.</p>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    @include('components.task.sidebar', [
    'task' => $task,
    'statuses' => $statuses,
    'priorities' => $priorities,
    'milestones' => $milestones,
    'teamMembers' => $teamMembers,
    'roles' => $roles
])
</div>
