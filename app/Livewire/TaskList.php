<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Livewire\WithPagination;

class TaskList extends Component
{
    use WithPagination;

    public $teamId;

    // Default visibility for columns
    public $columns;

    public $sortColumn = 'name'; // Default sort column
    public $sortDirection = 'asc'; // Default sort direction

    public function mount()
    {
        $this->initializeColumns();
    }
    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            // Toggle the sort direction if the column is already selected
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Otherwise, set the new sort column
            $this->sortColumn = $column;
            $this->sortDirection = 'asc'; // Default to ascending for a new column
        }
    }
    protected function initializeColumns()
    {
        $this->columns = auth()->user()->task_columns ?? [
            'priority' => true,
            'created_time' => true,
            'created_by' => true,
            'assigned_users' => true,
            'status' => true,
            'milestone' => true,
            'due_date' => true,
        ];

    }

    public function updateTaskColumns($updatedColumns)
    {

        $this->columns = $updatedColumns;

        // Save to the database
        $user = auth()->user();
        $user->task_columns = $updatedColumns;
        $user->save();
    }



    public function render()
    {
        $tasks = Task::query()
            ->whereHas('project.team', fn($query) => $query->where('id', $this->teamId))
            ->with([
                'priority:id,name',
                'creator:id,name',
                'assignees:id,name',
                'status:id,name',
                'milestone:id,name',
                'project:id,name',
            ])
            ->when($this->sortColumn === 'project.name', function ($query) {
                $query->join('projects as p', 'tasks.project_id', '=', 'p.id')
                    ->select('tasks.*')
                    ->orderBy('p.id', $this->sortDirection);
            })
            ->when($this->sortColumn === 'priority.name', function ($query) {
                $query->join('task_priorities as tp', 'tasks.task_priorities_id', '=', 'tp.id')
                    ->select('tasks.*')
                    ->orderBy('tp.id', $this->sortDirection); // Sort by task_priorities.id
            })
            ->when($this->sortColumn === 'm.name', function ($query) {
                $query->leftJoin('milestones as m', 'tasks.milestone_id', '=', 'm.id')
                    ->select('tasks.*')
                    ->orderByRaw("ISNULL(m.name) {$this->sortDirection}, m.name {$this->sortDirection}");
            })

            ->when($this->sortColumn === 'u.name', function ($query) {
                $query->join('users as u', 'tasks.created_by_user_id', '=', 'u.id')
                    ->select('tasks.*') // Keep the task data intact
                    ->orderBy('u.name', $this->sortDirection); // Sort by the alias
            })

            ->when(!in_array($this->sortColumn, ['project.name', 'priority.name']), function ($query) {
                $query->orderBy($this->sortColumn, $this->sortDirection);
            })
            ->get();

        return view('livewire.task-list', [
            'tasks' => $tasks,
            'columns' => $this->columns,
        ]);
    }

}

