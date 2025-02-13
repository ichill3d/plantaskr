@extends('layouts.sections.user')

@section('content')
    {{--    @include('components.dashboard-header', [--}}
    {{--           'title' => 'Organization Overview',--}}
    {{--           'action' => null--}}
    {{--       ])--}}


    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Organization Button -->
            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-2xl font-bold">Your Own Organizations</h1>
                <a href="{{ route('personal.organizations.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border
                border-transparent rounded-md font-semibold text-white text-sm hover:bg-blue-700 focus:outline-none
                focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Create New Organization
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

                @forelse ($ownTeams as $team)
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4">
                            <h2 class="text-lg font-semibold">
                                <a href="{{ route('organizations.overview', ['id' => $team->id, 'organization_alias' => $team->alias]) }}"
                                   class="text-blue-600 hover:underline font-medium">
                                    {{ $team->name }}
                                </a>
                            </h2>
                            <p class="text-gray-600">Owned by: {{ $team->owner->name }}</p>
                        </div>
                        <div class="bg-gray-100 px-6 py-4 flex justify-end items-center">
                            <a href="{{ route('organizations.overview',['id' => $team->id, 'organization_alias' => $team->alias]) }}"
                               class="text-blue-600 hover:underline font-medium">
                                View
                            </a>
                        </div>
                    </div>
                @empty
                    <p>No elements found in the array.</p>
                @endforelse
            </div>


                @forelse ($memberTeams as $team) ($memberTeams as $team)
            <h1 class="text-2xl font-bold mb-6">Organizations you are a member of</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4">
                            <h2 class="text-lg font-semibold">
                                <a href="{{ route('organizations.overview',['id' => $team->id, 'organization_alias' => $team->alias]) }}"
                                   class="text-blue-600 hover:underline font-medium">
                                    {{ $team->name }}
                                </a>
                            </h2>
                            <p class="text-gray-600">Owned by: {{ $team->owner->name }}</p>
                        </div>
                        <div class="bg-gray-100 px-6 py-4 flex justify-end items-center">
                            <a href="{{ route('organizations.overview',['id' => $team->id, 'organization_alias' => $team->alias]) }}"
                               class="text-blue-600 hover:underline font-medium">
                                View
                            </a>
                        </div>
                    </div>
            </div>
                @empty

                @endforelse


        </div>
    </div>

@endsection
