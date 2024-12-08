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

    <x-task-list ajax-url="{{ route('tasks.api', ['project_id' => $project->id]) }}" />
@endsection
