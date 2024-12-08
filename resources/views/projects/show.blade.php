@extends('layouts.dashboard')
@section('navTitle')
    {{ __('Project') }}
@endsection
@section('dashboadHeaderTitle')
    <span class="text-gray-800">{{ $project->name }}</span> — {{ __('Overview') }}
@endsection
@section('content')
    @include('components.dashboard-header', [
          'action' => [
              'url' => route('tasks.create'),
              'label' => 'Add New Task'
          ]
      ])
    <p>{{ $project->description }}</p>
@endsection
