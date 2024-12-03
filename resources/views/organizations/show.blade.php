<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Organization Details</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="mb-4"><strong>ID:</strong> {{ $organization->id }}</p>
            <p class="mb-4"><strong>Name:</strong> {{ $organization->name }}</p>
            <p class="mb-4"><strong>Description:</strong> {{ $organization->description }}</p>
            <p class="mb-4"><strong>Created At:</strong> {{ $organization->created_at->format('d-m-Y H:i') }}</p>
            <p class="mb-4"><strong>Updated At:</strong> {{ $organization->updated_at->format('d-m-Y H:i') }}</p>
        </div>

        <div class="mt-6">
            <a href="{{ route('organizations.edit', $organization->id) }}"
               class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                Edit Organization
            </a>
            <a href="{{ route('organizations.owned') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition ml-2">
                Back to List
            </a>
        </div>
    </div>
</x-app-layout>
