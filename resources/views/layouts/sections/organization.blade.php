@extends('layouts.app')

@section('sidebarTitle', $team->name)

@section('sidebar')
    <x-sidebar-nav-organization :team="$team" />
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
    <x-subpage subpageTitle="Organization Overview">
        <x-slot:content>
            <p>Welcome to the organization dashboard! Here is your organization-specific content.</p>
        </x-slot:content>
        <x-slot:extraContent>
            <p>Additional stats or resources for your organization can go here.</p>
        </x-slot:extraContent>
    </x-subpage>
@endsection
