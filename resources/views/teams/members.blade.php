@extends('layouts.organization')

@section('content')
    <h1 class="text-2xl font-bold">Organization Members</h1>
    <ul>
        @foreach ($members as $member)
            <li>{{ $member->name }} - {{ $member->email }}</li>
        @endforeach
    </ul>
@endsection
