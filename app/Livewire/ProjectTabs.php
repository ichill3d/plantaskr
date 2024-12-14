<?php

namespace App\Livewire;

use Livewire\Component;

class ProjectTabs extends Component
{
    public $project;
    public $team;
    public $tab;

    public function mount($project, $team, $tab = 'overview')
    {
        $this->project = $project;
        $this->tab = $tab;
        $this->team = $team;
    }

    public function setTab($tab)
    {
        $this->tab = $tab;

        // Debug to ensure the dispatch is called
        \Log::info('Dispatching update-url event', [
            'tab' => $tab,
            'url' => route('organizations.projects.show', [
                'project_id' => $this->project->id,
                'tab' => $tab,
                'id' => $this->team->id,
                'organization_alias' => $this->team->alias,
            ]),
        ]);

        // Update the browser's URL without reloading the page
        $this->dispatch('update-url', [
            'url' => route('organizations.projects.show', [
                'project_id' => $this->project->id,
                'tab' => $tab,
                'id' => $this->team->id,
                'organization_alias' => $this->team->alias,
            ]),
        ]);

    }

    public function render()
    {
        return view('livewire.project-tabs');
    }
}
