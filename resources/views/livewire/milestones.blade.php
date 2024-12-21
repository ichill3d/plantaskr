<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Milestones</h2>
        <button
            class="bg-blue-500 text-white px-4 py-2 rounded"
            wire:click="$set('showModal', true)"
        >
            Create Milestone
        </button>
    </div>


    <ul
        class="space-y-4"
        x-data
        x-sort="$wire.updateMilestoneOrder($event.detail.new)"
        x-ref="milestones"
    >
        @foreach ($milestones as $milestone)
            <li
                class="p-4 bg-gray-100 rounded shadow flex justify-between items-center cursor-move relative group"
                x-sort:item="{{ $milestone['id'] }}"
                x-data="{ editing: false, name: '{{ $milestone['name'] }}', description: '{{ $milestone['description'] }}' }"
            >
                <!-- Editable Content -->
                <div class="flex-1">
                    <div x-show="!editing" class="space-y-1">
                        <h3 class="text-lg font-semibold">{{ $milestone['name'] }}</h3>
                        <p class="text-gray-600">{{ $milestone['description'] }}</p>
                    </div>
                    <div x-show="editing" class="space-y-2">
                        <input
                            type="text"
                            x-model="name"
                            class="block w-full border-gray-300 rounded px-2 py-1 text-sm"
                        />
                        <textarea
                            x-model="description"
                            class="block w-full border-gray-300 rounded px-2 py-1 text-sm"
                        ></textarea>
                        <div class="flex space-x-2">
                            <button
                                @click="$wire.saveMilestone({{ $milestone['id'] }}, name, description); editing = false"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                            >
                                Save
                            </button>
                            <button
                                @click="editing = false"
                                class="bg-gray-300 text-gray-700 px-3 py-1 rounded hover:bg-gray-400"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="flex items-center space-x-2">
                    <button
                        @click="editing = true"
                        class="hidden group-hover:block text-gray-500 hover:text-gray-700"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 11-3.536-3.536l3.536 3.536zm-7.596 9.596a1.5 1.5 0 102.121-2.121l-7.596-7.596a1.5 1.5 0 00-2.121 2.121l7.596 7.596z" />
                        </svg>
                    </button>
                    <button
                        @click="if (document.querySelector('[x-data]')) {
                                    $dispatch('usermessage-show', {
                                        type: 'confirm',
                                        title: 'Confirm Milestone Deletion',
                                        message: 'Are you sure you want to delete this milestone?',
                                        action: () => { $wire.deleteMilestone({{ $milestone['id'] }}) }
                                    });
                                }"
                        class="hidden group-hover:block text-red-500 hover:text-red-700"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>
            </li>
        @endforeach
    </ul>


    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-10">
            <div class="bg-white p-6 rounded shadow-lg w-1/3">
                <h2 class="text-lg font-bold mb-4">Create New Milestone</h2>
                <form wire:submit.prevent="createMilestone">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium">Name</label>
                        <input
                            type="text"
                            id="name"
                            wire:model="newMilestone.name"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                        @error('newMilestone.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium">Description</label>
                        <textarea
                            id="description"
                            wire:model="newMilestone.description"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        ></textarea>
                        @error('newMilestone.description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="tasks" class="block text-sm font-medium">Attach Tasks</label>
                        <select
                            id="tasks"
                            wire:model="newMilestone.tasks"
                            multiple
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        >
                            @foreach($availableTasks as $task)
                                <option value="{{ $task['id'] }}">{{ $task['name'] }}</option>
                            @endforeach
                        </select>
                        @error('newMilestone.tasks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="button"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2"
                            wire:click="$set('showModal', false)"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
