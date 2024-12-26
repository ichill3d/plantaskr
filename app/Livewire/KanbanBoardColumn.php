<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;

class KanbanBoardColumn extends Component
{
    use WithPagination;

    public $teamId;
    public $statusId;
    public $perPage = 60;

    protected $listeners = [
        'refreshColumn' => 'loadTasks',
        'loadMoreTasks'
    ];

    public function mount($teamId, $statusId)
    {
        $this->teamId = $teamId;
        $this->statusId = $statusId;
        $this->loadTasks();
    }

    /**
     * Load tasks for the given status with pagination.
     */
    public function loadTasks()
    {
        $this->tasks = Task::with(['priority', 'assignees', 'project'])
            ->where('task_status_id', $this->statusId)
            ->whereHas('project.team', fn($query) => $query->where('id', $this->teamId))
            ->orderBy('board_position')
            ->paginate($this->perPage);
    }

    /**
     * Load more tasks on scroll.
     */
    public function loadMoreTasks()
    {
        $this->perPage += 20;
        $this->loadTasks();
    }

    public function render()
    {
        return view('livewire.kanban-board-column', [
            'tasks' => Task::with(['priority', 'assignees', 'project'])
                ->where('task_status_id', $this->statusId)
                ->whereHas('project.team', fn($query) => $query->where('id', $this->teamId))
                ->orderBy('board_position')
                ->paginate($this->perPage),
        ]);
    }
}
