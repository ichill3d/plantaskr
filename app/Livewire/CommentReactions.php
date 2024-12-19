<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CommentReaction;

class CommentReactions extends Component
{
    public $commentId;
    public $reactionTypeId = 1; // Default to "Like" for now
    public $reactionCount = 0;
    public $userHasReacted = false;

    public function mount($commentId)
    {
        $this->commentId = $commentId;

        $this->loadReactions();
    }

    public function loadReactions()
    {
        $this->reactionCount = CommentReaction::where('comments_id', $this->commentId)
            ->where('reactions_types_id', $this->reactionTypeId)
            ->count();

        $this->userHasReacted = CommentReaction::where('comments_id', $this->commentId)
            ->where('reactions_types_id', $this->reactionTypeId)
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function toggleReaction()
    {
        $existingReaction = CommentReaction::where('comments_id', $this->commentId)
            ->where('reactions_types_id', $this->reactionTypeId)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReaction) {
            $existingReaction->delete();
        } else {
            CommentReaction::create([
                'comments_id' => $this->commentId,
                'reactions_types_id' => $this->reactionTypeId,
                'user_id' => auth()->id(), // Ensure user_id is set
            ]);
        }

        $this->loadReactions();
    }


    public function render()
    {
        return view('livewire.comment-reactions');
    }
}

