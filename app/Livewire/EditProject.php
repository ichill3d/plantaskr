<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Project;

class EditProject extends Component
{
    public $project;
    public $name;
    public $description;
    public $color;
    public $teamMembers = [];
    public $assignedMembers = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'color' => 'required|string|max:7',
        'assignedMembers' => 'array',
        'assignedMembers.*' => 'exists:users,id',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->color = $project->color;
        $this->teamMembers = $project->team->users; // Fetch all team members
        $this->assignedMembers = $project->users->pluck('id')->toArray(); // IDs of assigned users
    }

    public function save()
    {
        $this->validate();

        // Update the project
        $this->project->update([
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
        ]);

        // Sync assigned users
        $this->project->users()->sync($this->assignedMembers);

        return redirect()->route('organizations.projects.show', [
            'id' => $this->project->team->id,
            'organization_alias' => $this->project->team->alias,
            'project_id' => $this->project->id,
            'tab' => 'settings',
        ]);
    }
    public function updatedColor()
    {
        // Update color in real-time
        $this->project->update([
            'color' => $this->color,
        ]);

        session()->flash('color_success', 'Project color updated successfully.');

    }
    public function deleteProject($projectId)
    {
        $project = Project::find($projectId);
        $project->delete();
        return redirect()->route('organizations.projects', [
            'id' => $project->team->id,
            'organization_alias' => $project->team->alias,
        ], 301);
    }
    public function render()
    {
        return view('livewire.edit-project');
    }
}
