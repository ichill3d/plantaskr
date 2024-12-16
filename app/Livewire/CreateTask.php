<?php

namespace App\Livewire;

use App\Models\Team;
use Livewire\Component;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskStatus;
use App\Models\TaskPriority;
use App\Models\Role;
use App\Models\User;

class CreateTask extends Component
{
    public $name;
    public $description;
    public $project_id;
    public $task_status_id;
    public $priority_id;
    public $user_ids = [];
    public $roles = [];
    public $projects;
    public $statuses;
    public $priorities;
    public $users;
    public $showModal = false;
    public $currentTeamId;

    public function mount($currentTeamId)
    {
        $team = Team::find($currentTeamId);
        $this->projects = $team->projects;
        $this->statuses = TaskStatus::all();
        $this->priorities = TaskPriority::all();
        $this->currentTeamId = $currentTeamId;

        // Fetch users who belong to the specified team
        $this->users = $team->users->concat([$team->creator]);

        // Set a default project_id if there's only one project
        if ($this->projects->count() === 1) {
            $this->project_id = $this->projects->first()->id;
        }
    }

    public function save()
    {

        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'priority_id' => 'required|exists:task_priorities,id',
        ]);

        $task = Task::create([
            'name' => $this->name,
            'description' => $this->description,
            'project_id' => $this->project_id,
            'task_status_id' => 1,
            'task_priorities_id' => $this->priority_id,
        ]);

        $syncData = [
            auth()->id() => ['role_id' => 1], // Author role
        ];
        // Assign users and roles
        if(!empty($this->user_ids)){
            foreach ($this->user_ids as $key => $user_id) {
                $syncData[$user_id] = ['role_id' => 2];
            }
        }

        $task->users()->sync($syncData);

        // Reset form and close modal
        $this->reset();
        $this->showModal = false;

        session()->flash('success', 'Task created successfully.');
        $this->dispatch('taskCreated');
    }

    public function render()
    {
        return view('livewire.create-task');
    }
}
