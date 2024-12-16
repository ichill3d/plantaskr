@extends('layouts.sections.organization')

@section('content')
        <!-- Project Header -->
        <div class="bg-white shadow-sm rounded-lg mb-6 p-4">
            <!-- Project Label -->
            <div style="background-color: {{ $project->color }}; color: {{ get_contrast_color($project->color) }}"
                 class="block text-xs font-medium mr-2 px-3 py-1 rounded-xl uppercase tracking-wide">
                Project
            </div>
            <div class="flex items-center justify-between py-2">
                <!-- Left Section: Project Label and Title -->
                <div>


                    <!-- Project Title -->
                    <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $project->name }}</h1>
                </div>

                <!-- Right Section: Livewire Button -->
                <div>
                    <livewire:create-task :currentTeamId="$team->id" :project_id="$project->id ?? null" />
                </div>
            </div>
        </div>


        <!-- Tab Navigation -->
        <livewire:project-tabs :project="$project" :tab="$tab" :team="$team" />



@endsection
