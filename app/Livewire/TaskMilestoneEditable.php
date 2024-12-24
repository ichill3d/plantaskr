<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\Milestone;

class TaskMilestoneEditable extends Component
{
    public Task $task;
    public $milestones;
    public $selectedMilestone;

    public function mount($taskId)
    {
        $this->task = Task::find($taskId);
        $this->milestones =  $this->task->project?->milestones ?? collect();
        $this->selectedMilestone =  $this->task->milestone->id ?? null;
    }

    public function updateMilestone($milestoneId)
    {
        $milestone = Milestone::find($milestoneId);

        if ($milestone) {
            $this->task->milestone()->associate($milestone);
            $this->task->save();

            $this->selectedMilestone = $milestoneId;
            $this->dispatch('milestoneUpdated', $milestoneId);
            session()->flash('success', 'Task milestone updated successfully!');
        }
    }

    public function render()
    {
        return view('livewire.task-milestone-editable');
    }
}
