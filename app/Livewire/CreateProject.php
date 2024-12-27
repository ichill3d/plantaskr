<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Project;


class CreateProject extends Component
{
    public $name;
    public $description;
    public $color;
    public $teamId;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:60000',
        'color' => 'required|string|max:7', // Ensure this is a valid hex color
    ];

    public function save()
    {
        $this->validate();

        // Create the project
        $project = Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'team_id' => $this->teamId,
        ]);

        // Redirect to the project's team page
        return redirect()->route('organizations.projects.show', [
            'id' => $project->team_id,
            'organization_alias' => $project->team->alias ?? null,
            'project_id' => $project->id,
        ], 301);
    }

    public function updatedDescription($value)
    {
        $this->dispatch('refreshQuillContent', ['name' => 'description', 'content' => $value]);
    }

    public function render()
    {
        return view('livewire.create-project');
    }
}
