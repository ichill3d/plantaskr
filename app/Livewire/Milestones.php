<?php

namespace App\Livewire;

use App\Models\Milestone;
use App\Models\Task;
use Livewire\Component;

class Milestones extends Component
{
    public $projectId;
    public $milestones = [];
    public $newMilestone = [
        'name' => '',
        'description' => '',
        'tasks' => [],
    ];
    public $availableTasks = [];
    public $showModal = false;

    protected $rules = [
        'newMilestone.name' => 'required|max:255',
        'newMilestone.description' => 'required|max:255',
        'newMilestone.tasks' => 'array',
        'newMilestone.tasks.*' => 'integer',
    ];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->loadMilestones();
        $this->loadAvailableTasks();
    }

    public function loadMilestones()
    {
        $this->milestones = Milestone::where('projects_id', $this->projectId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function loadAvailableTasks()
    {
        $this->availableTasks = Task::where('project_id', $this->projectId)
            ->whereNull('milestone_id') // Adjust if your schema uses a different column name
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();
    }

    public function createMilestone()
    {
        $this->validate();

        $milestone = Milestone::create([
            'projects_id' => $this->projectId,
            'name' => $this->newMilestone['name'],
            'description' => $this->newMilestone['description'],
        ]);

        // Attach selected tasks to the milestone
        Task::whereIn('id', $this->newMilestone['tasks'])->update(['milestone_id' => $milestone->id]);

        // Reset form and reload data
        $this->newMilestone = ['name' => '', 'description' => '', 'tasks' => []];
        $this->showModal = false;
        $this->loadMilestones();
        $this->loadAvailableTasks();
    }

}
