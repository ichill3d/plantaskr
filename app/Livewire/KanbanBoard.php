<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;
use App\Models\TaskStatus;

class KanbanBoard extends Component
{
    public $teamId;
    public $statuses = [];
    public $selectedTask;

    protected $listeners = [
        'refreshBoard', 'updateTaskStatus'
    ];

    public function mount($teamId)
    {
        $this->teamId = $teamId;
        $this->loadStatuses();
    }

    /**
     * Load all task statuses dynamically from the database.
     */
    public function loadStatuses()
    {
        $this->statuses = TaskStatus::orderBy('id')->get();
    }

    public function refreshBoard()
    {
        $this->loadStatuses();
        $this->dispatch('refreshBoard');
    }
    public function updateTaskStatus($taskId, $statusId, $position)
    {
        $task = Task::find($taskId);

        if ($task) {
            $previousStatusId = $task->task_status_id;

            // Update the task's status if it moved to a new column
            if ($previousStatusId != $statusId) {
                $task->update(['task_status_id' => $statusId]);
            }

            // Fetch tasks in the target column and reorder
            $tasks = Task::where('task_status_id', $statusId)
                ->whereHas('project.team', fn($q) => $q->where('id', $this->teamId))
                ->orderBy('board_position')
                ->get();

            $tasks = $tasks->reject(fn($t) => $t->id == $task->id);
            $tasks->splice($position, 0, [$task]);

            foreach ($tasks as $index => $t) {
                $t->update(['board_position' => $index + 1]);
            }

            // Refresh only the two affected columns
            $this->dispatch('refreshColumn', ['statusId' => $previousStatusId]);
            $this->dispatch( 'refreshColumn', ['statusId' => $statusId]);
        }
    }





    public function render()
    {
        return view('livewire.kanban-board', [
            'statuses' => $this->statuses,
        ]);
    }
}
