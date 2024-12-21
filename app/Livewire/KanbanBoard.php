<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class KanbanBoard extends Component
{
    public $teamId;

    public $tasks = []; // Holds all tasks grouped by status

    protected $listeners = ['updateTaskStatus'];


    public function mount($teamId)
    {
        $this->teamId = $teamId;

        $this->loadTasks();
    }

    public function loadTasks()
    {
        $query = Task::query()
            ->with(['priority', 'status', 'project', 'assignees'])
            ->whereHas('project.team', fn($q) => $q->where('id', $this->teamId))
            ->get()->groupBy('task_status_id')
            ->toArray();
        $this->tasks = $query;
    }

    public function updateTaskStatus($taskId, $statusId)
    {
        if ($taskId && $statusId) {
            $task = Task::find($taskId);
            if ($task) {
                $task->update(['task_status_id' => $statusId]);
                $this->loadTasks(); // Reload tasks to reflect the change
            }
        }
    }




    public function render()
    {
        return view('livewire.kanban-board', [
            'statuses' => [
                1 => 'Pending',
                2 => 'In Progress',
                3 => 'In Review',
                4 => 'Done',
            ],
        ]);
    }
}
