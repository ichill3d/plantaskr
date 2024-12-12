@extends('layouts.sections.user')

@section('content')
    {{--    @include('components.dashboard-header', [--}}
    {{--           'title' => 'Organization Overview',--}}
    {{--           'action' => null--}}
    {{--       ])--}}
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('teams.create-team-form')
        </div>
    </div>
@endsection
