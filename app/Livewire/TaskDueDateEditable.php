<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Carbon;

class TaskDueDateEditable extends Component
{
    public Task $task;
    public $dueDate;

    public function mount($taskId)
    {
        $this->task = Task::find($taskId);
        $this->dueDate = $this->task->due_date ? $this->task->due_date->format('Y-m-d') : null;
    }

    public function updateDueDate($date)
    {
        $parsedDate = $date ? Carbon::parse($date) : null;

        $this->task->due_date = $parsedDate;
        $this->task->save();

        $this->dueDate = $parsedDate ? $parsedDate->format('Y-m-d') : null;

        $this->dispatch('dueDateUpdated', $parsedDate);
        session()->flash('success', 'Task due date updated successfully!');
    }

    public function render()
    {
        return view('livewire.task-due-date-editable');
    }
}
