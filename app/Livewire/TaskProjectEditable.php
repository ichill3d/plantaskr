<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\Project;

class TaskProjectEditable extends Component
{
    public Task $task;
    public $projects;
    public $searchQuery = '';

    public function mount(Task $task)
    {
        $this->task = $task;
        $this->projects = Project::where('team_id', $task->team->id)->get();
    }

    public function updateProject($projectId)
    {
        $project = Project::find($projectId);

        if ($project) {
            $this->task->project()->associate($project);
            $this->task->save();

            $this->dispatch('projectUpdated', $projectId);
            session()->flash('success', 'Task project updated successfully!');
        }
    }

    public function render()
    {
        return view('livewire.task-project-editable', [
            'filteredProjects' => $this->searchQuery
                ? $this->projects->filter(fn($project) => str_contains(strtolower($project->name), strtolower($this->searchQuery)))
                : $this->projects
        ]);
    }
}
