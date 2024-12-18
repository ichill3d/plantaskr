<div>
    <!-- Tab Navigation -->
    <nav class="flex border-b border-gray-200 bg-gray-50">
        <button
            wire:click="setTab('overview')"
            class="py-2 px-4 text-sm font-medium text-gray-600 border-b-2 {{ $tab === 'overview' ? 'border-blue-500 text-blue-500' : 'border-transparent hover:text-gray-800 hover:border-gray-300' }}">
            Overview
        </button>
        <button
            wire:click="setTab('discussion')"
            class="py-2 px-4 text-sm font-medium text-gray-600 border-b-2 {{ $tab === 'discussion' ? 'border-blue-500 text-blue-500' : 'border-transparent hover:text-gray-800 hover:border-gray-300' }}">
            Discussion
        </button>
        <button
            wire:click="setTab('files')"
            class="py-2 px-4 text-sm font-medium text-gray-600 border-b-2 {{ $tab === 'files' ? 'border-blue-500 text-blue-500' : 'border-transparent hover:text-gray-800 hover:border-gray-300' }}">
            Files
        </button>

        <button
            wire:click="setTab('tasks')"
            class="py-2 px-4 text-sm font-medium text-gray-600 border-b-2 {{ $tab === 'tasks' ? 'border-blue-500 text-blue-500' : 'border-transparent hover:text-gray-800 hover:border-gray-300' }}">
            Tasks
        </button>
        <button
            wire:click="setTab('milestones')"
            class="py-2 px-4 text-sm font-medium text-gray-600 border-b-2 {{ $tab === 'milestones' ? 'border-blue-500 text-blue-500' : 'border-transparent hover:text-gray-800 hover:border-gray-300' }}">
            Milestones
        </button>
{{--        <button--}}
{{--            wire:click="setTab('members')"--}}
{{--            class="py-2 px-4 text-sm font-medium text-gray-600 border-b-2 {{ $tab === 'members' ? 'border-blue-500 text-blue-500' : 'border-transparent hover:text-gray-800 hover:border-gray-300' }}">--}}
{{--            Members--}}
{{--        </button>--}}
        <button
            wire:click="setTab('settings')"
            class="py-2 px-4 text-sm font-medium text-gray-600 border-b-2 {{ $tab === 'settings' ? 'border-blue-500 text-blue-500' : 'border-transparent hover:text-gray-800 hover:border-gray-300' }}">
            Settings
        </button>
    </nav>

    <!-- Tab Content -->
    <div class="p-6 bg-white shadow rounded-lg mt-4">
        @if ($tab === 'overview')
            <div>
                <p class="text-gray-600 mt-2">{!!  $project->description !!}</p>
            </div>
        @elseif ($tab === 'discussion')
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Discussion</h2>
                <p class="text-gray-600 mt-2">Content for the Discussion tab.</p>
            </div>
        @elseif ($tab === 'files')
            <div>
                <livewire:file-upload :projectId="$project->id" />
            </div>
        @elseif ($tab === 'tasks')
            <div>
                <livewire:task-list :project-id="$project->id" />
            </div>
        @elseif ($tab === 'milestones')
            <div>
                <livewire:milestones :project-id="$project->id" />
            </div>
{{--        @elseif ($tab === 'members')--}}
{{--            <div>--}}
{{--                <h2 class="text-lg font-semibold text-gray-800">Members</h2>--}}
{{--                <p class="text-gray-600 mt-2">Content for the Members tab.</p>--}}
{{--            </div>--}}
        @elseif ($tab === 'settings')
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Project Settings</h2>
                <livewire:edit-project :project="$project" />
            </div>
        @endif
    </div>
</div>

<script>
    function registerUrlListener() {
        document.addEventListener('update-url', event => {
            const detail = event.detail[0]; // Access the first item in the array
            if (detail?.url) {
                history.pushState(null, '', detail.url);
            }
        });
    }
    // Register listener on page load
    registerUrlListener();

    // Re-register listener after Livewire updates
    document.addEventListener('livewire:load', () => {
        registerUrlListener();
    });
</script>
