@extends('layouts.sections.organization')
@section('content')

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">


            @livewire('teams.team-member-manager', ['team' => $team])


        </div>
    </div>
@endsection
