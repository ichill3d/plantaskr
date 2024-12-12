<?php

namespace App\Livewire;

use Livewire\Component;

class TableView extends Component
{
    public $data; // The dataset to display
    public $team; // team data
    public $model; // Model class for data fetching
    public $columns = []; // Columns configuration
    public $dataType; // Data type identifier (e.g., users, projects, etc.)

    protected $listeners = ['reloadTable'];

    public function mount(string $model, array $columns, string $dataType, array $team)
    {
        $this->model = $model;
        $this->columns = $columns;
        $this->dataType = $dataType;
        $this->team = $team;

        $this->loadData();
    }

    public function loadData()
    {
        $this->data = app($this->model)::all();
    }


    public function reloadTable()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.table-view')
            ->layout('layouts.app');
    }
}
