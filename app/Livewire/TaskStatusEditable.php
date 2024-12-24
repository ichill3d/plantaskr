<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\TaskStatus;

class TaskStatusEditable extends Component
{
    public Task $task;
    public $statuses = [];
    public $selectedStatus;

    public function mount($taskId)
    {
        $this->task = Task::findOrFail($taskId);
        $this->statuses = TaskStatus::all();
        $this->selectedStatus = $this->task->status->id ?? null;
    }

    public function updateStatus($statusId)
    {
        $status = TaskStatus::find($statusId);

        if ($status) {
            $this->task->status()->associate($status);
            $this->task->save();

            $this->selectedStatus = $statusId;
            $this->dispatch('reloadTask', ['taskId' => $this->task->id]);
            $this->dispatch('reloadTaskSidebar', ['taskId' => $this->task->id]);
        }
    }

    public function render()
    {
        return view('livewire.task-status-editable');
    }
}
