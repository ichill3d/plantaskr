<div class="flex items-center space-x-2">
    <button
        wire:click="toggleReaction"
        class="text-blue-500 hover:text-blue-700"
    >
        👍
    </button>
    <span>{{ $reactionCount }}</span>
</div>
