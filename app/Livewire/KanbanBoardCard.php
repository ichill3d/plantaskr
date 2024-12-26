<?php

namespace App\Livewire;

use Livewire\Component;

class KanbanBoardCard extends Component
{
    public $task;
    protected $listeners = ['reloadTask' => 'reloadIfMatchingTask'];

    public function mount($task)
    {
        $this->task = $task;
    }

    public function reloadIfMatchingTask($data)
    {

        if ($data['taskId'] == $this->task->id) {
            $this->task->refresh();
        }
    }

    public function render()
    {
        return view('livewire.kanban-board-card', [
            'task' => $this->task,
        ]);
    }
}
