<x-app-layout>
    <h1>Edit Project</h1>
    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="{{ $project->name }}" required>
        <label for="description">Description</label>
        <textarea id="description" name="description" required>{{ $project->description }}</textarea>
        <button type="submit">Update</button>
    </form>
</x-app-layout>
