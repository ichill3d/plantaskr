<div>

    @foreach ($projects as $project)
        <div class="bg-white mb-2 shadow-md rounded-lg flex flex-col lg:flex-row lg:items-center justify-between hover:bg-gray-50 p-3"
             style="border-left: 15px solid {{ $project->color }};">
            <!-- Left Section: Project Info -->
            <div class="flex-1 min-w-0">
                <h2 class="text-lg font-semibold text-gray-800 truncate">
                    <a class="text-blue-600 hover:underline" href="{{ route('organizations.projects.show', [
                        'id' => $team->id,
                        'organization_alias' => $team->alias,
                        'project_id' => $project->id,
                    ]) }}">{{ $project->name }}</a>
                </h2>
            </div>

            <!-- Middle Section: Project Stats -->
            <div class="flex items-center space-x-4 mt-3 lg:mt-0 lg:ml-6 text-sm text-gray-600">
                <div class="flex items-center space-x-2">
                    <span class="font-medium">{{ $project->tasks_count }}</span>
                    <span>Tasks</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="font-medium">{{ $project->milestones_count }}</span>
                    <span>Milestones</span>
                </div>
{{--                <div class="flex items-center space-x-2">--}}
{{--                    <span class="font-medium">{{ $project->users_count }}</span>--}}
{{--                    <span>Users</span>--}}
{{--                </div>--}}
                <div class="flex items-center space-x-2">
                    <p class="text-sm text-gray-600">Created: {{ $project->created_at->format('Y-m-d') }}</p>
                </div>
            </div>

            <!-- Right Section: Actions -->

        </div>
    @endforeach
</div>

