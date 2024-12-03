<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Organization</h1>

        <!-- Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('organizations.update', $organization->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" id="name"
                           class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                           value="{{ old('name', $organization->name) }}" required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">{{ old('description', $organization->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <a href="{{ route('organizations.owned') }}"
                       class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition mr-2">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
