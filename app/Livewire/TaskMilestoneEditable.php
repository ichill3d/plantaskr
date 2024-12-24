<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\Milestone;

class TaskMilestoneEditable extends Component
{
    public Task $task;
    public $milestones = [];
    public $selectedMilestone;

    public function mount($taskId)
    {
        $this->task = Task::findOrFail($taskId);
        $this->milestones = Milestone::all();
        $this->selectedMilestone = $this->task->milestone->id ?? null;
    }

    public function updateMilestone($milestoneId)
    {
        if ($milestoneId === null) {
            $this->task->milestone()->dissociate();
        } else {
            $milestone = Milestone::find($milestoneId);
            if ($milestone) {
                $this->task->milestone()->associate($milestone);
            }
        }

        $this->task->save();
        $this->selectedMilestone = $milestoneId;
        $this->dispatch('reloadTask', ['taskId' => $this->task->id]);
        $this->dispatch('reloadTaskSidebar', ['taskId' => $this->task->id]);
    }


    public function render()
    {
        return view('livewire.task-milestone-editable');
    }
}
