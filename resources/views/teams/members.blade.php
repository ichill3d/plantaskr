@extends('layouts.sections.organization')

@section('content')
    @include('components.dashboard-header', [
          'title' => 'Members',
          'action' => [
              'url' => route('projects.create'),
              'label' => 'Manage Members'
          ]
      ])
    {{--    <x-project-list ajax-url="{{ route('projects.api') }}" />--}}
    <livewire:table-view
        :model="'App\\Models\\User'"
        :columns="[
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'status', 'label' => 'Status']
    ]"
        :dataType="'members'"
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
