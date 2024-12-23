<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\TaskStatus;

class TaskStatusEditable extends Component
{
    public Task $task;
    public $statuses;
    public $selectedStatus;

    public function mount(Task $task)
    {
        $this->task = $task;
        $this->statuses = TaskStatus::all();
        $this->selectedStatus = $task->status->id ?? null;
    }

    public function updateStatus($statusId)
    {
        $status = TaskStatus::find($statusId);

        if ($status) {
            $this->task->status()->associate($status);
            $this->task->save();

            $this->selectedStatus = $statusId;
            $this->dispatch('statusUpdated', $statusId);
            session()->flash('success', 'Task status updated successfully!');
        }
    }

    public function render()
    {
        return view('livewire.task-status-editable');
    }
}
