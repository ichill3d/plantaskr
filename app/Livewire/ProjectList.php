<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;

class ProjectList extends Component
{
    public $team;
    public $projects;

    public function mount($team)
    {
        $this->team = $team;
        $this->projects = Project::where('team_id', $team->id)->get();
    }

    public function render()
    {
        return view('livewire.project-list');
    }
}
