@extends('layouts.sections.organization')
@section('content')
<livewire:show-task :taskId="$task->id" />
@endsection
