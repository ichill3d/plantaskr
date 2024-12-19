<div>
       <!-- Comment Form -->
    <form wire:submit.prevent="addComment" class="mb-4">
        <x-tiptap
            wire:model="content"
            taskId="{{ $parentId }}"
        ></x-tiptap>
        @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        <button
            type="submit"
            class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
        >
            Post Comment
        </button>
    </form>

    <!-- Comment List -->
    <div class="space-y-4">
        @forelse($comments as $comment)
            <div class="p-4 border rounded-md">
                <div class="text-sm text-gray-600">{{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}</div>
                <div class="prose">{!! $comment->content !!}</div>
                <livewire:comment-reactions :commentId="$comment->id" />
            </div>
        @empty
            <p class="text-gray-500">No comments yet.</p>
        @endforelse
    </div>
</div>
