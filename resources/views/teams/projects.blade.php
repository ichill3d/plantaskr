@extends('layouts.sections.organization')


@section('content')


    <!-- Page Header -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Projects</h1>

    <!-- Project List -->
    <div class="space-y-4">
        <!-- Dummy Project Cards -->
        @foreach (range(1, 6) as $index)
            <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between">
                <!-- Left Section: Project Info -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Project {{ $index }}</h2>
                    <p class="text-sm text-gray-600">Created: {{ now()->subDays($index)->format('Y-m-d') }}</p>
                </div>

                <!-- Middle Section: Project Stats -->
                <div class="flex items-center space-x-8 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium">{{ rand(10, 100) }}</span>
                        <span>Tasks</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="font-medium">{{ rand(1, 10) }}</span>
                        <span>Milestones</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="font-medium">{{ rand(3, 20) }}</span>
                        <span>Users</span>
                    </div>
                </div>

                <!-- Right Section: Actions -->
                <div class="flex space-x-4">
                    <a href="#"
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                        View
                    </a>
                    <a href="#"
                       class="text-sm text-gray-600 hover:text-gray-800 font-medium hover:underline">
                        Edit
                    </a>
                </div>
            </div>
        @endforeach
    </div>




    @include('components.dashboard-header', [
          'title' => 'Projects',
          'action' => [
              'url' => route('organizations.create-project', ['id' => $team->id, 'organization_alias' => $team->alias]),
              'label' => 'Add New Project'
          ]
      ])
{{--    <x-project-list ajax-url="{{ route('projects.api') }}" />--}}
    <livewire:table-view
        :model="'App\\Models\\Project'"
        :columns="[
        ['key' => 'id', 'label' => 'ID'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'description', 'label' => 'Description'],
        ['key' => 'created_at', 'label' => 'Created At']
    ]"
        :dataType="'projects'"
        :team="['id' => $team->id, 'alias' => $team->alias]"
    />
    <button id="reloadTableButton" class="bg-blue-500 text-white px-4 py-2 rounded">Reload Table</button>


    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#reloadTableButton').on('click', function () {
                Livewire.dispatch('reloadTable');
            });
        });
    </script>
@endsection
