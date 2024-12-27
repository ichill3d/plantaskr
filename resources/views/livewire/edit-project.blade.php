<div class="space-y-8">

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="p-4 text-green-800 bg-green-100 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Project Settings Form -->
    <form wire:submit.prevent="save" class="space-y-6">

        <!-- Project Details -->
        <div class="p-6 bg-white border rounded-md shadow-sm">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Project Details</h2>

            <!-- Project Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                <input wire:model="name" type="text" id="name"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <x-tiptap
                    wire:model="description"
                    projectId="{{ $project->id }}"
                ></x-tiptap>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600">
                    Save Changes
                </button>
            </div>
        </div>

        <!-- Appearance Settings -->
        <div class="p-6 bg-white border rounded-md shadow-sm">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Appearance</h2>

            <!-- Project Color (Debounced Update with Auto-Hide) -->
            <div class="mb-4" x-data="{ color: '{{ $color }}', timer: null }">
                <label for="color" class="block text-sm font-medium text-gray-700">Project Color</label>
                <input
                    type="color"
                    id="color"
                    x-model="color"
                    @input.debounce.500ms="
            clearTimeout(timer);
            timer = setTimeout(() => {
                $wire.set('color', color).then(() => {
                    $el.blur(); // Hide the color picker after saving
                });
            }, 500);
        "
                    @change="$el.blur()"
                    class="mt-1 block w-20 h-10 border-gray-300 rounded-md shadow-sm"
                >
                @error('color')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if (session()->has('color_success'))
                    <p class="text-green-500 text-sm mt-1">{{ session('color_success') }}</p>
                @endif
            </div>




        </div>

        <!-- Save Changes Button (For Name and Description Only) -->

    </form>

    <!-- Danger Zone: Project Deletion -->
    <div class="p-6 bg-red-50 border border-red-200 rounded-md shadow-sm">
        <h2 class="text-lg font-semibold text-red-600 mb-4 border-b border-red-200 pb-2">Danger Zone</h2>

        <p class="text-sm text-red-700 mb-4">
            Deleting this project is irreversible. All associated data will be permanently removed.
        </p>

        <button
            type="button"
            @click.stop="$dispatch('usermessage-show', {
                type: 'confirm',
                title: 'Confirm Project Deletion',
                message: 'Are you sure you want to delete this Project?',
                action: () => {
                    $wire.deleteProject({{ $project->id }})
                }
            })"
            class="bg-red-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-red-600"
        >
            Delete Project
        </button>
    </div>
</div>
