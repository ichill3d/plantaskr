<div class="grid grid-cols-[2fr_1fr] gap-4">
    <!-- Main Content -->
    <div class="overflow-auto">
        <!-- Task Title -->
        <a href="{{ route('organizations.projects.show', [
            'id' => $task->team->id,
            'organization_alias' => $task->team->alias,
            'project_id' => $task->project->id
        ]) }}"
           style="background-color: {{ $task->project->color }}; color: {{ get_contrast_color($task->project->color) }}"
           class="block text-xs font-medium mr-2 px-3 py-2 rounded-md uppercase tracking-wide mb-2 hover:opacity-75">
            <span class="opacity-50">Project:</span> {{ $task->project->name }}
        </a>

        <div x-data="{ editing: false, title: '{{ $task->name }}' }">
            <h1
                x-show="!editing"
                class="text-2xl font-bold text-gray-800 mb-4 cursor-pointer"
                @click="editing = true"
            >
                {{ $task->name }}
            </h1>
            <div x-show="editing" class="mb-4 w-full">
                <input
                    type="text"
                    x-model="title"
                    class="text-xl w-full font-bold text-gray-800 mb-4 border-b border-gray-400 focus:outline-none focus:border-blue-500"
                />
                <div class="flex space-x-2 mt-2">
                    <button
                        @click="$wire.updateTaskTitle({{ $task->id }}, title); editing = false"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    >
                        Save
                    </button>
                    <button
                        @click="editing = false; title = '{{ $task->name }}'"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Task Description -->
        <div class="text-gray-600 mb-6 border rounded-md p-4">
            @if (!$isEditingDescription)
                <div class="cursor-pointer"  wire:click="toggleEditDescription">

                    {!! $description !!}
                </div>
            @else
                <div>
                    <x-quill-editor name="description" />
                    <div class="mt-2">
                        <button wire:click="saveDescription" class="btn btn-primary">Save</button>
                        <button wire:click="$set('isEditingDescription', false)" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            @endif
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
