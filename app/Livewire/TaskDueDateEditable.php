<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Carbon\Carbon;

class TaskDueDateEditable extends Component
{
    public Task $task;
    public $dueDate;

    public function mount($taskId)
    {
        $this->task = Task::findOrFail($taskId);
        $this->dueDate = $this->task->due_date ? $this->task->due_date->format('Y-m-d') : null;
    }

    public function updateDueDate(bool $deleteDueDate = false)
    {
        logger('Received date:', ['dueDate' => $this->dueDate]);
            // Use the bound $this->dueDate
        if($deleteDueDate) {
            $this->task->due_date = null;
        } else {
            $this->task->due_date = Carbon::parse($this->dueDate);
        }
        $this->task->save();
        $this->dispatch('reloadTask', ['taskId' => $this->task->id]);
        $this->dispatch('reloadTaskSidebar', ['taskId' => $this->task->id]);
    }

    public function render()
    {
        return view('livewire.task-due-date-editable');
    }
}
