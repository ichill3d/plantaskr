<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Organizations</h1>

        <!-- Button to Create New Organization -->
        <div class="mb-6">
            <a href="{{ route('organizations.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Add Organization
            </a>
        </div>

        <!-- Organizations Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($ownedOrganizations as $organization)
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2"><a href="{{ route('organizations.show', $organization->id) }}"
                                                                            class="hover:underline">{{ $organization->name }}  </a></h2>
                    <p class="text-gray-600 text-sm mb-4">{{ $organization->description }}</p>
                    <div class="flex justify-between">

                        <!-- Edit Button -->
                        <a href="{{ route('organizations.edit', $organization->id) }}"
                           class="text-yellow-500 hover:underline text-sm">
                            Edit
                        </a>
                        <form action="{{ route('organizations.destroy', $organization->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this organization?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">
                    No organizations found.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
