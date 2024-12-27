<div class="space-y-6" x-data="{ showEditor: false }" x-on:comment-posted.window="showEditor = false">

    <!-- Add Comment Button -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Discussion</h2>
        <button
            x-on:click="showEditor = !showEditor"
            class="px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600"
        >
            <span x-text="showEditor ? 'Cancel' : 'Add a Comment'"></span>
        </button>
    </div>

    <!-- Comment Form with Slide Transition -->
    <div
        x-show="showEditor"
        x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-y-4 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transform transition ease-in-out duration-200"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-4 opacity-0"
        class="mb-6 border p-4 rounded-md bg-gray-50 shadow-md"
    >
        <form wire:submit.prevent="addComment">
            <x-tiptap
                wire:model="content"
                taskId="{{ $parentId }}"
            ></x-tiptap>
            @error('content')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            <button
                type="submit"
                class="mt-4 px-4 py-2 bg-green-500 text-white font-medium rounded-md hover:bg-green-600"
            >
                Post Comment
            </button>
        </form>
    </div>

    <!-- Comment List -->
    <div class="space-y-4">
        @forelse($comments as $comment)
            <div class="p-4 border rounded-md shadow-sm bg-white">
                <div class="flex justify-between items-center mb-2">
                    <div class="text-sm font-medium text-gray-700">
                        {{ $comment->user->name }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $comment->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="prose prose-sm text-gray-800">
                    {!! $comment->content !!}
                </div>
                <div class="mt-2">
                    <livewire:comment-reactions :commentId="$comment->id" />
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center">No comments yet. Be the first to share your thoughts!</p>
        @endforelse
    </div>
</div>
