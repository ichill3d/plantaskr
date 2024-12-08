@extends('layouts.dashboard')

@section('navTitle')
    {{ __('Organization') }}
@endsection

@section('dashboadHeaderTitle')
    {{ $team->name }} → {{ __('Overview') }}
@endsection

@section('content')
    @include('components.dashboard-header', [
           'title' => 'Organization Overview',
           'action' => null
       ])
    test
@endsection
