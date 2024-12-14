@extends('layouts.sections.organization')

@section('content')

        <!-- Project Header -->
        <div class="bg-white shadow-sm rounded-lg mb-6">
            <!-- Project Label -->
            <div class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-3 py-1 rounded-xl uppercase tracking-wide">
                Project
            </div>

            <!-- Project Title and Description -->
            <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $project->name }}</h1>
            <p class="text-gray-600 mt-2">{{ $project->description }}</p>
        </div>

        <!-- Tab Navigation -->
        <livewire:project-tabs :project="$project" :tab="$tab" :team="$team" />

@endsection
