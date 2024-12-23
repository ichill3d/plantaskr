<div x-data="{ open: false, searchQuery: '' }" class="relative">
    <!-- Display Current Project -->
    <button
        @click="open = !open"
        class="text-sm text-gray-700 hover:underline hover:text-blue-600"
    >
        {{ $task->project->name ?? 'N/A' }}
    </button>

    <!-- Project Dropdown -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition.opacity.duration.300ms
        class="absolute mt-2 bg-white border shadow-lg rounded-md w-64 max-h-64 z-10"
    >
        <!-- Dropdown Header -->
        <div class="flex items-end justify-between px-4 py-2 border-b">
            <div class="font-semibold text-sm">Select a Project</div>
            <button
                @click="open = false"
                class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-2 py-1 text-sm"
            >
                OK
            </button>
        </div>

        <!-- Search Box -->
        <div class="p-2">
            <input
                type="text"
                x-model="searchQuery"
                placeholder="Search projects..."
                class="w-full px-2 py-1 border rounded-md text-sm focus:ring focus:ring-blue-500 "
            />
        </div>

        <!-- Project List -->
        <div class="overflow-y-auto max-h-40">
            @foreach ($filteredProjects as $project)
                <div
                    @click="$wire.updateProject({{ $project->id }}); open = false"
                    x-show="searchQuery === '' || '{{ strtolower($project->name) }}'.includes(searchQuery.toLowerCase())"
                    class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer"
                >
                    <span class="ml-2 text-sm">{{ $project->name }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div
            x-data="{ visible: true }"
            x-init="setTimeout(() => visible = false, 2000)"
            x-show="visible"
            x-transition.opacity.duration.500ms
            class="mt-2 text-xs text-green-500"
        >
            {{ session('success') }}
        </div>
    @endif
</div>
