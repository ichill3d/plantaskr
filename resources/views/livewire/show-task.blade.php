<div class="grid grid-cols-[2fr_1fr] gap-4">
    <!-- Main Content -->
    <div class="overflow-auto">
        <!-- Task Title -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $task->name }}</h1>

        <!-- Task Description -->
        <div class="text-gray-600 mb-6">
            <p>
                {{ $task->description }}
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
    <div class="bg-gray-100 shadow rounded-lg p-6 space-y-4">
        <!-- Metadata -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Metadata</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li><div class="font-semibold">Date Created:</div> <span>{{ $task->created_at }}</span></li>
                <li><div class="font-semibold">Project:</div> <span>{{ $task->project->name }}</span></li>
                <li><div class="font-semibold">Milestone:</div> <span>{{ $task->milestone?->name ?? "add to milestone" }}</span></li>
                <li><div class="font-semibold">Assignees:</div>
                    <span>
                       @foreach ($task->assignees as $assignee)
                            <div class="relative w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center">
                                @if ($assignee->profile_photo_path)
                                    <img src="{{ $assignee->profile_photo_path }}" alt="{{ $assignee->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-sm font-semibold text-gray-700">
                                    {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </span>
                </li>
                <li><div class="font-semibold">Priority:</div> <span>{{ $task->priority->name }}</span></li>
                <li><div class="font-semibold">Status:</div> <span>{{ $task->status->name }}</span></li>
            </ul>
        </div>
    </div>
</div>
