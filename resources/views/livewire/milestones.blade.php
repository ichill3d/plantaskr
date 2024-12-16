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

    <!-- List Milestones -->
    <ul class="space-y-4">
        @foreach($milestones as $milestone)
            <li class="p-4 bg-gray-100 rounded shadow">
                <h3 class="text-lg font-semibold">{{ $milestone['name'] }}</h3>
                <p class="text-gray-600">{{ $milestone['description'] }}</p>
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
