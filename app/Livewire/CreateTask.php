<?php

namespace App\Livewire;

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
    public $task_priorities_id;
    public $user_ids = [];
    public $roles = [];
    public $projects;
    public $statuses;
    public $priorities;
    public $users;
    public $showModal = false;

    public function mount($currentTeamId)
    {
        $this->projects = Project::all();
        $this->statuses = TaskStatus::all();
        $this->priorities = TaskPriority::all();

        // Fetch users who belong to the specified team
        $this->users = User::whereHas('teams', function ($query) use ($currentTeamId) {
            $query->where('team_id', $currentTeamId);
        })->get();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'task_status_id' => 'required|exists:task_statuses,id',
            'task_priorities_id' => 'required|exists:task_priorities,id',
        ]);

        $task = Task::create([
            'name' => $this->name,
            'description' => $this->description,
            'project_id' => $this->project_id,
            'task_status_id' => $this->task_status_id,
            'task_priorities_id' => $this->task_priorities_id,
        ]);

        // Assign users and roles
        foreach ($this->user_ids as $key => $user_id) {
            $task->users()->attach($user_id, ['role_id' => $this->roles[$key]]);
        }

        // Reset form and close modal
        $this->reset();
        $this->showModal = false;

        session()->flash('success', 'Task created successfully.');
        $this->emit('taskCreated');
    }

    public function render()
    {
        return view('livewire.create-task');
    }
}
