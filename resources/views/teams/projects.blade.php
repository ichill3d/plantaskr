@extends('layouts.sections.organization')
@section('content')
    <x-dashboard-header title="Projects">
        <livewire:create-project :teamId="$team->id" />
    </x-dashboard-header>

    <div class="space-y-4">
        <livewire:project-list :team="$team" />
    </div>
@endsection
