@extends('layouts.dashboard')
@section('content')
    @include('components.dashboard-header', [
          'title' => 'Discussion'
      ])
    discussion
@endsection
