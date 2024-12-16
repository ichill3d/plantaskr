<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class ListTeamTasks extends Component
{
    use WithPagination;

    public $team; // The team passed as a prop
    public $sortColumn = 'name'; // Default sort column
    public $sortDirection = 'asc'; // Default sort direction

    public function mount($team)
    {
        $this->team = $team;
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            // Toggle sort direction
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Change sort column and reset to ascending
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $tasks = Task::whereHas('project.team', function ($query) {
            $query->where('id', $this->team->id);
        })
            ->when($this->sortColumn === 'task_priorities_id', function ($query) {
                // Join task_priorities table if sorting by priority
                $query->join('task_priorities', 'tasks.task_priorities_id', '=', 'task_priorities.id')
                    ->select('tasks.*') // Ensure only main table fields are selected
                    ->orderBy('task_priorities.name', $this->sortDirection);
            }, function ($query) {
                // Default sorting for columns in tasks table
                $query->orderBy($this->sortColumn, $this->sortDirection);
            })
            ->with('project', 'priority') // Eager-load relationships
            ->paginate(3);

        return view('livewire.list-team-tasks', compact('tasks'));
    }

}
