@extends('layouts.sections.organization')

@section('content')

    <livewire:create-task :currentTeamId="$team->id" />

    <p>{{ $project->description }}</p>
@endsection
