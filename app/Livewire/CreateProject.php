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
        Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'team_id' => $this->teamId,
        ]);

        // Emit event to notify parent components or reset form
        $this->reset(['name', 'description', 'color', 'showModal']);
        $this->dispatch('projectCreated');

        session()->flash('success', 'Project created successfully!');
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
