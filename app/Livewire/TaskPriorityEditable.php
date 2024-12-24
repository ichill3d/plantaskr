<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\TaskPriority;

class TaskPriorityEditable extends Component
{
    public Task $task;
    public $priorities = [];
    public $selectedPriority;

    public function mount(int $taskId)
    {
        $this->task = Task::findOrFail($taskId);
        $this->priorities = TaskPriority::all();
        $this->selectedPriority = $this->task->priority->id ?? null;
    }

    public function updatePriority($priorityId)
    {
        $priority = TaskPriority::find($priorityId);

        if ($priority) {
            $this->task->priority()->associate($priority);
            $this->task->save();

            $this->selectedPriority = $priorityId;

            // Dispatch events
            $this->dispatch('reloadTask', ['taskId' => $this->task->id]);
            $this->dispatch('reloadTaskSidebar', ['taskId' => $this->task->id]);
        }
    }

    public function render()
    {
        return view('livewire.task-priority-editable');
    }
}
