@extends('teams.dashboard')

@section('content')
    <h1 class="text-2xl font-bold">Organization Projects</h1>
    <ul>
        @foreach ($projects as $project)
            <li>{{ $project->name }} - {{ $project->description }}</li>
        @endforeach
    </ul>
@endsection
