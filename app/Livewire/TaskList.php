<?php
namespace App\Livewire;

use App\Models\TaskPriority;
use App\Models\TaskStatus;
use Livewire\Component;
use App\Models\Task;
use Livewire\WithPagination;

class TaskList extends Component
{
    use WithPagination;

    public $teamId;
    public $projectId;

    // Default visibility for columns
    public $columns;

    public $sortColumn = 'name'; // Default sort column
    public $sortDirection = 'asc'; // Default sort direction

    public $selectedStatuses = [];
    public $selectedPriorities = [];
    public $selectedUsers = [];
    public $selectedProjects = [];

    public $selectedTask;

    protected $listeners = [
        'taskPriorityUpdated' => '$refresh',
        'filterUpdated' => '$refresh',
        'taskAssigneesUpdated' => '$refresh'
    ];


    // Bind filters and sorting to the query string
    protected $queryString = [
        'selectedUsers' => ['except' => []],
        'selectedProjects' => ['except' => []],
        'selectedStatuses' => ['except' => []],
        'selectedPriorities' => ['except' => []],
        'sortColumn' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];
    public function filterUpdated($filters = null)
    {
        logger('filterUpdated triggered successfully:', [$filters]);
    }

    public function mount()
    {
        $this->initializeColumns();
        $this->initializeStateFromQuery();
    }
    public function updateFilter(array $filters)
    {

        logger('Filters received in updateFilter:', $filters);

        $this->selectedUsers = $filters['selectedUsers'] ?? [];
        $this->selectedProjects = $filters['selectedProjects'] ?? [];

    }

    private function initializeStateFromQuery()
    {
        $this->selectedUsers = collect(request()->query('selectedUsers', []))
            ->map(fn($id) => (int) $id) // Normalize to integers
            ->values()
            ->toArray();

        $this->selectedProjects = collect(request()->query('selectedProjects', []))
            ->map(fn($id) => (int) $id)
            ->values()
            ->toArray();

        $this->selectedStatuses = collect(request()->query('selectedStatuses', []))
            ->map(fn($id) => (int) $id)
            ->values()
            ->toArray();

        $this->selectedPriorities = collect(request()->query('selectedPriorities', []))
            ->map(fn($id) => (int) $id)
            ->values()
            ->toArray();

        $this->sortColumn = request()->query('sortColumn', 'name');
        $this->sortDirection = request()->query('sortDirection', 'asc');
    }



    public function clearAllFilters()
    {
        $this->selectedUsers = [];
        $this->selectedProjects = [];
        $this->selectedStatuses = []; // Add other filters here if applicable
        $this->selectedPriorities = []; // Add other filters here if applicable
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


    public function updateTaskPriority($taskId, $priorityId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->task_priorities_id = $priorityId;
            $task->save();
            session()->flash('success', 'Task priority updated successfully.');
        }
    }
    public function updateTaskStatus($taskId, $statusId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->task_status_id = $statusId; // Update status
            $task->save();
            session()->flash('success', 'Task status updated successfully.');
        }
    }
    public function updateTaskMilestone($taskId, $milestoneId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->milestone_id = $milestoneId;
            $task->save();
            session()->flash('success', 'Assigned to milstone successfully.');
        }
    }
    public function updateTaskDueDate($taskId, $dueDate)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->due_date = $dueDate; // Update due date
            $task->save();
            session()->flash('success', 'Due Date changed successfully.');
        }
    }
    public function assignUserToTask($taskId, $userId)
    {
        $task = Task::find($taskId);
        if ($task && !$task->assignees->contains($userId)) {
            $task->assignees()->attach($userId, ['role_id' => 2]);
            session()->flash('success', 'User Assigned successfully.');
        }
    }

    public function unassignUserFromTask($taskId, $userId)
    {
        $task = Task::find($taskId);
        if ($task && $task->assignees->contains($userId)) {
            $task->assignees()->detach($userId);
            session()->flash('success', 'User removed from task.');
        }
    }

    public function render()
    {

        $enabledColumns = collect($this->columns)->filter(fn($isEnabled) => $isEnabled);
        $lastColumn = $enabledColumns->keys()->last();
        $enabledColumnsCount = $enabledColumns->count();

        $tasks = Task::query()
            ->when(!empty(array_values($this->selectedUsers)), function ($query) {
                $query->whereHas('users', function ($query) {
                    //logger('Inside users whereHas:', array_values($this->selectedUsers));
                    $query->whereIn('users.id', array_values($this->selectedUsers));
                });
            });

        //logger('Generated Query:', [$tasks->toSql()]);


        $tasks = Task::query()
            ->whereHas('project.team', fn($query) => $query->where('id', $this->teamId))
            ->when($this->projectId, fn($query) => $query->where('project_id', $this->projectId)) // Filter by projectId
            ->when(!empty($this->selectedStatuses), fn($query) => $query->whereIn('task_status_id', $this->selectedStatuses)) // Filter by selected statuses
            ->when(!empty($this->selectedPriorities), fn($query) => $query->whereIn('task_priorities_id', $this->selectedPriorities)) // Filter by selected priorities
            ->when(!empty($this->selectedUsers), fn($query) => $query->whereHas('assignees', fn($query) => $query->whereIn('users.id', array_values($this->selectedUsers)))) // Filter by assigned users
            ->when(!empty($this->selectedProjects), fn($query) => $query->whereIn('project_id', $this->selectedProjects)) // Filter by selected projects
            ->with([
                'priority:id,name',
                'creator:id,name',
                'assignees:id,name,profile_photo_path',
                'status:id,name',
                'milestone:id,name',
                'project:id,name,color',
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

        $team = auth()->user()->currentTeam;

        $statuses = TaskStatus::all(); // Fetch available statuses
        $priorities = TaskPriority::all(); // Fetch available priorities
        $users = $team->members()
            ->get()
            ->merge([$team->creator]); // Get team members
        $projects = $team->projects()->get(); // Get team projects

        return view('livewire.task-list', [
            'tasks' => $tasks,
            'statuses' => $statuses,
            'priorities' => $priorities,
            'users' => $users,
            'projects' => $projects,
            'columns' => $this->columns,
            'enabledColumns' => $enabledColumns,
            'lastColumn' => $lastColumn,
            'enabledColumnsCount' => $enabledColumnsCount,
        ]);
    }


}

