@extends('layouts.sections.organization')


@section('content')
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
