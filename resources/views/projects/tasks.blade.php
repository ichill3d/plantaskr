@extends('layouts.dashboard')
@section('navTitle')
    Project
@endsection
@section('dashboadHeaderTitle')
    <span class="text-gray-800">{{ $project->name }}</span> — {{ __('Tasks') }}
@endsection
@section('content')
    @include('components.dashboard-header', [
          'title' => 'Tasks',
          'action' => [
              'url' => route('tasks.create'),
              'label' => 'Add New Task'
          ]
      ])

    <livewire:table-view
        :model="'App\Models\Task'"
        :columns="[
        ['key' => 'id', 'label' => 'ID'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'description', 'label' => 'Description'],
        ['key' => 'created_at', 'label' => 'Created At']
    ]"
        :dataType="'tasks'"
        :team="['id' => 7, 'alias' => 'asdasfasf']"
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
