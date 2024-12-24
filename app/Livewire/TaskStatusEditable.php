<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\TaskStatus;

class TaskStatusEditable extends Component
{
    public $taskId;
    public Task $task;
    public $statuses;
    public $selectedStatus;

    public function mount($taskId)
    {
        $this->taskId = $taskId;
        $this->task = Task::find($taskId);
        $this->statuses = TaskStatus::all();
        $this->selectedStatus = $this->task->status->id ?? null;
    }

    public function updateStatus($statusId)
    {
        $status = TaskStatus::find($statusId);
        $this->task = Task::find($this->taskId);
        if ($status) {
            $this->task->status()->associate($status);
            $this->task->save();

            $this->selectedStatus = $statusId;
            $this->dispatch('statusUpdated', $statusId);
//            session()->flash('success', 'Task status updated successfully!');
        }
    }

    public function render()
    {
        return view('livewire.task-status-editable');
    }
}
