<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\TaskPriority;

class TaskPriorityEditable extends Component
{
    public Task $task;
    public $priorities;
    public $selectedPriority;

    public function mount(Task $task)
    {
        $this->task = Task::find($task->id ?? null);
        $this->priorities = TaskPriority::all(); // Using Priority model here
        $this->selectedPriority = $task->priority->id ?? null;
    }

    public function updatePriority($priorityId)
    {
        $priority = TaskPriority::find($priorityId);

        if ($priority) {
            $this->task->priority()->associate($priority);
            $this->task->save();

            $this->selectedPriority = $priorityId;
            $this->dispatch('priorityUpdated', $priorityId);
            session()->flash('success', 'Task priority updated successfully!');
        }
    }

    public function render()
    {
        return view('livewire.task-priority-editable');
    }
}
