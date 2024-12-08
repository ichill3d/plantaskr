@extends('layouts.dashboard')

@section('navTitle')
{{ __('Organization') }}
@endsection

@section('dashboadHeaderTitle')
    {{ $team->name }} → {{ __('Projects') }}
@endsection

@section('content')
    @include('components.dashboard-header', [
          'title' => 'Projects',
          'action' => [
              'url' => route('projects.create'),
              'label' => 'Add New Project'
          ]
      ])
    <x-project-list ajax-url="{{ route('projects.api') }}" />
@endsection
