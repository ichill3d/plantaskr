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
    @include('components.task.sidebar', [
    'task' => $task,
    'statuses' => $statuses,
    'priorities' => $priorities,
    'milestones' => $milestones,
    'teamMembers' => $teamMembers,
    'roles' => $roles
])
</div>
