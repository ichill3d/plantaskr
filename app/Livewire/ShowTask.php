<?php

namespace App\Livewire;

use App\Models\TaskRole;
use Livewire\Component;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TaskPriority;
use App\Models\Milestone;
use App\Models\User;



class ShowTask extends Component
{
    public $task;
    public $statuses;
    public $priorities;
    public $milestones;
    public $teamMembers;
    public $roles;


    public function mount($task)
    {
        $this->task = Task::with(['project:id,name', 'priority:id,name', 'assignees:id,name,profile_photo_path'])->findOrFail($task->id);
        $this->statuses = TaskStatus::all();
        $this->priorities = TaskPriority::all();
        $this->milestones = $task->project->milestones ?? [];
        $this->teamMembers = $task->project->team->users
            ->concat([$task->project->team->creator]) // Include team creator
            ->unique('id'); // Remove duplicates
        $this->roles = TaskRole::all(); // Fetch all roles


    }

    public function toggleAssignee($userId)
    {
        // Toggle user assignment
        if ($this->task->assignees->contains($userId)) {
            $this->task->assignees()->detach($userId);
            session()->flash('success', 'User removed from task.');
        } else {
            $this->task->assignees()->attach($userId, ['role_id' => 2]); // Default role_id: 2
            session()->flash('success', 'User assigned to task.');
        }

        $this->task->refresh();
    }
    public function assignMilestone($milestoneId)
    {
        $milestone = Milestone::find($milestoneId);
        if ($milestone) {
            $this->task->milestone_id = $milestone->id;
            $this->task->save();
            session()->flash('success', 'Milestone updated successfully.');
        }
    }

    public function changeStatus($statusId)
    {
        // Validate and save the new status
        $status = TaskStatus::find($statusId);
        if ($status) {
            $this->task->task_status_id = $status->id;
            $this->task->save();

            session()->flash('success', 'Task status updated successfully.');
        }
    }

    public function changePriority($priorityId)
    {
        $priority = TaskPriority::find($priorityId);
        if ($priority) {
            $this->task->task_priorities_id = $priority->id;
            $this->task->save();
            session()->flash('success', 'Task priority updated successfully.');
        }
    }

    public function render()
    {
        return view('livewire.show-task');
    }
}
