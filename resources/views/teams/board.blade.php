@extends('layouts.sections.organization')


@section('content')

    <x-dashboard-header title="Kanban Board">
    </x-dashboard-header>
    <livewire:kanban-board :team-id="$team->id"  :selectedTask="$selectedTask" />
@endsection
