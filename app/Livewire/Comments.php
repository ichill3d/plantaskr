<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use Mews\Purifier\Facades\Purifier;

class Comments extends Component
{
    public $type; // Type of parent (tasks or projects)
    public $parentId; // ID of the parent (task_id or project_id)
    public $content; // New comment content
    public $comments = []; // List of comments

    protected $rules = [
        'content' => 'required|string|max:1000',
    ];

    public function mount($type, $parentId)
    {
        $this->type = $type;
        $this->parentId = $parentId;

        // Load existing comments
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = Comment::where('parent_type', $this->type)
            ->where('parent_id', $this->parentId)
            ->latest()
            ->get();
    }

    public function addComment()
    {
        $this->validate();
        $sanitizedContent = Purifier::clean($this->content);


        // Create a new comment
        Comment::create([
            'parent_type' => $this->type,
            'parent_id' => $this->parentId,
            'user_id' => auth()->id(),
            'content' => $sanitizedContent,
        ]);

        $this->dispatch('comment-posted');
        // Reset the input and reload comments
        $this->content = '';
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.comments');
    }
}
