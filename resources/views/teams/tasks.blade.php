@extends('layouts.sections.organization')


@section('content')
    <x-dashboard-header title="Tasks">
        <livewire:create-task :currentTeamId="$team->id" />
    </x-dashboard-header>
    <livewire:task-list :team-id="$team->id" />
@endsection

{{--@section('content')--}}
{{--    @extends('layouts.sections.organization')--}}

{{--    @section('content')--}}
{{--        <x-dashboard-header title="Tasks">--}}
{{--            --}}{{--        <livewire:create-project :teamId="$team->id" />--}}
{{--        </x-dashboard-header>--}}
{{--    <livewire:table-view--}}
{{--        :model="'App\\Models\\Task'"--}}
{{--        :columns="[--}}
{{--        ['key' => 'id', 'label' => 'ID'],--}}
{{--        ['key' => 'name', 'label' => 'Name'],--}}
{{--        ['key' => 'description', 'label' => 'Description'],--}}
{{--        ['key' => 'created_at', 'label' => 'Created At']--}}
{{--    ]"--}}
{{--        :dataType="'teamTasks'"--}}
{{--        :team="['id' => $team->id, 'alias' => $team->alias]"--}}
{{--    />--}}
{{--    <button id="reloadTableButton" class="bg-blue-500 text-white px-4 py-2 rounded">Reload Table</button>--}}


{{--    @livewireScripts--}}
{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function () {--}}
{{--            $('#reloadTableButton').on('click', function () {--}}
{{--                Livewire.dispatch('reloadTable');--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endsection--}}
