<div class="">
    <!-- Header Section -->
    <div class="flex items-center mb-2 space-x-2 relative">
        <!-- Project Link -->
        <a href="{{ route('organizations.projects.show', [
            'id' => $task->team->id,
            'organization_alias' => $task->team->alias,
            'project_id' => $task->project->id
        ]) }}"
           style="background-color: {{ $task->project->color }}; color: {{ get_contrast_color($task->project->color) }};"
           class="flex-grow text-xs font-medium px-3 py-1 rounded-md uppercase tracking-wide block"
        >
            <div class="opacity-50 hover:opacity-100">
                Project: {{ $task->project->name }}
            </div>
        </a>
        <!-- Close Button -->
        <button
            @click="
                Livewire.dispatch('closeTaskModal');
                open = false;
                if('{{$origin}}' == 'taskList') {
                    history.pushState({}, '', '{{ route('organizations.tasks', ['id' => $task->team->id, 'organization_alias' => $task->team->alias]) }}');
                } else {
                    history.pushState({}, '', '{{ route('organizations.board', ['id' => $task->team->id, 'organization_alias' => $task->team->alias]) }}');
                }
{{--                --}}
            "
            class="text-gray-500 hover:text-gray-700 p-1 px-2 rounded-lg bg-gray-100 hover:bg-gray-200"
        >
            ✕
        </button>
    </div>

    <!-- Task Title -->
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

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-[2fr_1fr] gap-4 h-[calc(100vh-17rem)] overflow-hidden">

        <!-- Main Content Area (Scrollable) -->
        <div class="overflow-y-auto p-4 bg-white rounded-md border border-gray-200 h-[calc(100vh-17rem)] ">
            <!-- Task Description -->
            @if (!$isEditingDescription)
                <div class="cursor-pointer prose prose-compact" wire:click="toggleEditDescription">
                    {!! $description !!}
                </div>
            @else
                <div>
                    <x-tiptap wire:model="description" taskId="{{ $task->id }}"></x-tiptap>
                    <div class="mt-2 flex space-x-2">
                        <button wire:click="saveDescription" class="btn btn-primary">Save</button>
                        <button wire:click="$set('isEditingDescription', false)" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            @endif

            <!-- Discussion Section -->
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Discussion</h2>
                <livewire:comments type="tasks" :parentId="$task->id" />
            </div>
        </div>

        <!-- Sidebar (Sticky + Scrollable) -->
        <div class="bg-gray-100 shadow rounded-lg p-6 space-y-4 sticky top-4 overflow-y-auto h-[calc(100vh-6rem)]">
           <livewire:task-sidebar :task="$task" />
            <!-- Files Section -->
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Files</h2>
                <livewire:file-upload :taskId="$task->id" />
            </div>
        </div>
    </div>

</div>
