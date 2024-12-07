<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">Your Own Organizations</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach ($ownTeams as $team)
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4">
                            <h2 class="text-lg font-semibold"><a href="{{ route('teams.dashboard', $team->id) }}" class="text-blue-600 hover:underline font-medium">{{ $team->name }}</a></h2>
                            <p class="text-gray-600">Owned by: {{ $team->owner->name }}</p>
                        </div>
                        <div class="bg-gray-100 px-6 py-4 flex justify-end items-center">
                            <a href="{{ route('teams.dashboard', $team->id) }}" class="text-blue-600 hover:underline font-medium">
                                View
                            </a>

                        </div>
                    </div>
                @endforeach
            </div>

            <h1 class="text-2xl font-bold mb-6">Organizations you are a member of</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($memberTeams as $team)
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4">
                            <h2 class="text-lg font-semibold"><a href="/organization/{{ $team->id }}" class="text-blue-600 hover:underline font-medium">{{ $team->name }}</a></h2>
                            <p class="text-gray-600">Owned by: {{ $team->owner->name }}</p>
                        </div>
                        <div class="bg-gray-100 px-6 py-4 flex justify-end items-center">
                            <a href="/teams/{{ $team->id }}" class="text-blue-600 hover:underline font-medium">
                                View
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

