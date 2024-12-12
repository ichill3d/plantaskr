<div>

    <!-- Trigger Button -->
    <button wire:click="$set('showModal', true)" class="btn btn-primary">Create New Task</button>

    <!-- Modal -->
    @if($showModal)
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40" wire:click="$set('showModal', false)"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6">
                    <h2 class="text-xl font-bold mb-4">Create New Task</h2>

                    <form wire:submit.prevent="save">
                        <!-- Task Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Task Name</label>
                            <input wire:model="name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Project -->
                        <div class="mb-4">
                            <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                            <select wire:model="project_id" id="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="" disabled>Select a project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                            @error('project_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Add other fields similarly... -->

                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end">
                            <button type="button" wire:click="$set('showModal', false)" class="btn btn-secondary mr-2">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
