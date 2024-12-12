@extends('layouts.app')

@section('sidebarTitle', 'User Profile')

@section('sidebar')
    <x-sidebar-nav-profile />
@endsection

@section('sidebarFooter')
    <x-sidebar-bottom-link />
@endsection

@section('headerLeft')
    <x-search-bar />
@endsection

@section('headerRight')
    <x-user-top-panel />
@endsection

@section('content')
    <x-subpage subpageTitle="Your Profile">
        <x-slot:content>
            <p>This is your personal profile page. Update your details here.</p>
        </x-slot:content>
        <x-slot:extraContent>
            <p>Additional information about your account or preferences can go here.</p>
        </x-slot:extraContent>
    </x-subpage>
@endsection
