<?php

namespace App\Livewire;

use App\Models\TaskRole;
use Livewire\Component;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TaskPriority;
use App\Models\Milestone;
use App\Models\User;
use Mews\Purifier\Facades\Purifier;




class ShowTask extends Component
{
    public $task;
    public $statuses;
    public $priorities;
    public $milestones;
    public $teamMembers;
    public $roles;
    public $description;
    public $isEditingDescription = false;



    public function mount($task)
    {
        $this->task = Task::with(['project:id,name,color', 'priority:id,name', 'assignees:id,name,profile_photo_path'])->findOrFail($task->id);

        $this->task->description = Purifier::clean($this->task->description);
        $this->description = $this->task->description;
        $this->statuses = TaskStatus::all();
        $this->priorities = TaskPriority::all();
        $this->milestones = $task->project->milestones ?? [];
        $this->teamMembers = $task->project->team->users
            ->concat([$task->project->team->creator]) // Include team creator
            ->unique('id'); // Remove duplicates
        $this->roles = TaskRole::all(); // Fetch all roles


    }

    public function deleteTask()
    {
        $this->task->delete();

        session()->flash('success', 'Task deleted successfully.');

        return redirect()->route('organizations.projects.show',
            ['id' => $this->task->team->id,
                'organization_alias' => $this->task->team->alias,
                'project_id' => $this->task->project->id,
                'tab' => 'tasks']);
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

    public function toggleEditDescription()
    {
        $this->isEditingDescription = !$this->isEditingDescription;

        if ($this->isEditingDescription) {
            // Dispatch event to reinitialize the editor with current content
            $this->dispatch('refreshQuillContent', [
                'name' => 'description',
                'content' => $this->description,
            ]);
        }
    }
    public function saveDescription()
    {
        $this->validate([
            'description' => 'required|string|min:3',
        ]);

        $this->task->update(['description' => $this->description]);
        $this->isEditingDescription = false;

        session()->flash('success', 'Description updated successfully.');
    }

    public function render()
    {
        return view('livewire.show-task');
    }
}
