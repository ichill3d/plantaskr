<x-app-layout>
    <h1>{{ $project->name }}</h1>
    <p>{{ $project->description }}</p>
    <a href="{{ route('projects.edit', $project) }}">Edit</a>
</x-app-layout>
