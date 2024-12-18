

    @if (session()->has('success'))
        <div class="mb-4 p-4 text-green-800 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
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
            <x-quill-editor name="description" />
            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Project Color -->
        <div class="mb-4">
            <label for="color" class="block text-sm font-medium text-gray-700">Project Color</label>
            <input wire:model="color" type="color" id="color"
                   class="mt-1 block w-20 h-10 border-gray-300 rounded-md shadow-sm">
            @error('color') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Assign Team Members -->
{{--        <div class="mb-4">--}}
{{--            <label class="block text-sm font-medium text-gray-700">Team Members</label>--}}
{{--            <div class="mt-2 space-y-2">--}}
{{--                @foreach ($teamMembers as $member)--}}
{{--                    <div class="flex items-center space-x-3">--}}
{{--                        <input wire:model="assignedMembers" type="checkbox" value="{{ $member->id }}"--}}
{{--                               id="member_{{ $member->id }}"--}}
{{--                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">--}}
{{--                        <label for="member_{{ $member->id }}" class="text-gray-800">{{ $member->name }}</label>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--            @error('assignedMembers') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror--}}
{{--        </div>--}}

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
{{--            <button type="button" wire:click="$emit('cancelEditing')" class="btn btn-secondary mr-3">Cancel</button>--}}
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>

