<x-app-layout>
    <div class="py-8">
        <h1 class="text-2xl font-bold">{{ $task->name }}</h1>
        <p>{{ $task->description }}</p>
        <p>Project: {{ $task->project->name }}</p>
        <p>Status: {{ $task->status->name }}</p>
        <p>Priority: {{ $task->priority->name }}</p>
        <p>Assigned Users:</p>
        <ul>
            @foreach ($task->users as $user)
                <li>{{ $user->name }}</li>
            @endforeach
        </ul>
        <p>Labels:</p>
        <ul>
            @foreach ($task->labels as $label)
                <li>{{ $label->name }}</li>
            @endforeach
        </ul>
        <p>Attachments:</p>
        <ul>
            @foreach ($task->attachments as $attachment)
                <li><a href="{{ asset($attachment->file_path) }}" target="_blank">View File</a></li>
            @endforeach
        </ul>
        <p>Links:</p>
        <ul>
            @foreach ($task->links as $link)
                <li>{{ $link->type->name }}</li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
