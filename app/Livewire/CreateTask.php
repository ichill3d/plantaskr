<?php

namespace App\Livewire;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TaskPriority;

class CreateTask extends Component
{
    public $name;
    public $description;
    public $project_id;
    public $task_status_id;
    public $due_date;
    public $priority_id;
    public $user_ids = [];
    public $roles = [];
    public $projects;
    public $statuses;
    public $priorities;
    public $users;
    public $showModal = false;
    public $currentTeamId;

    protected $listeners = ['taskCreated' => '$refresh'];

    public function mount($currentTeamId, $project_id = null)
    {
        $team = Team::findOrFail($currentTeamId);

        $this->projects = $team->projects;
        $this->statuses = TaskStatus::all();
        $this->priorities = TaskPriority::all();
        $this->currentTeamId = $currentTeamId;

        // Fetch users and ensure uniqueness
        $this->users = $team->users->concat([$team->creator])->unique('id');

        // Set default project and status
        if ($this->projects->count() === 1) {
            $this->project_id = $this->projects->first()->id;
        }
        $this->task_status_id = $this->statuses->first()->id ?? null;

        $this->project_id = $project_id ?? ($this->projects->count() === 1 ? $this->projects->first()->id : null);

    }
    public function updatedShowModal($value)
    {
        if ($value) {

            if (is_null($this->project_id) && $this->projects->count() === 1) {
                $this->project_id = $this->projects->first()->id;
            }

            $this->dispatch('modal-open');


            $this->dispatch('showTheModal'); // Dispatch a custom event when modal is shown
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string', // Allow HTML content
            'project_id' => ['required', function ($attribute, $value, $fail) {
                if (!$this->projects->pluck('id')->contains($value)) {
                    $fail('The selected project is invalid.');
                }
            }],
            'priority_id' => 'required|exists:task_priorities,id',
            'user_ids' => 'array|exists:users,id',
        ]);

        $task = Task::create([
            'name' => $this->name,
            'description' => $this->description,
            'project_id' => $this->project_id,
            'task_status_id' => $this->task_status_id,
            'task_priorities_id' => $this->priority_id,
            'created_by_user_id' => Auth::id(), // Ensure logged-in user is always set
        ]);

        if (!empty($this->user_ids)) {
            foreach ($this->user_ids as $userId) {
                $task->assignees()->attach($userId, ['role_id' => 2]);
            }
        }

        $this->reset(['name', 'description', 'project_id', 'priority_id', 'user_ids']);
        $this->showModal = false;

        session()->flash('success', 'Task created successfully.');
        $this->dispatch('taskCreated');

        return redirect()->route('organizations.projects.show',
            ['id' => $task->team->id,
                'organization_alias' => $task->team->alias,
                'project_id' => $task->project->id,
                'tab' => 'tasks']);
    }




    public function render()
    {
        return view('livewire.create-task');
    }
}
