<div>
    <!-- Trigger Button -->
    <button wire:click="$set('showModal', true)" class="btn btn-primary">Create New Project</button>

    <!-- Modal -->
    @if($showModal)
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40" wire:click="$set('showModal', false)"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6">
                    <h2 class="text-xl font-bold mb-4">Create New Project</h2>

                    <form wire:submit.prevent="save">
                        <!-- Project Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                            <input wire:model="name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <x-tiptap
                                wire:model="description"
                            ></x-tiptap>
                            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Color -->
                        <div class="mb-4">
                            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                            <input wire:model="color" type="color" id="color" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('color') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

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
