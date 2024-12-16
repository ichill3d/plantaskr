<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class ShowTask extends Component
{
    public $task;

    public function mount($task)
    {
        $this->task = Task::with(['project:id,name', 'priority:id,name', 'assignees:id,name,profile_photo_path'])->findOrFail($task->id);
    }

    public function render()
    {
        return view('livewire.show-task');
    }
}
