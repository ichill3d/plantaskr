<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class KanbanBoard extends Component
{
    public $teamId;

    public $tasks = []; // Holds all tasks grouped by status

    public $selectedTask;

    protected $listeners = [
        'updateTaskStatus',
        'refreshBoard',
        'refreshTask'
    ];

    public function refreshBoard()
    {
        $this->loadTasks();
    }
    public function mount($teamId)
    {
        $this->teamId = $teamId;

        $this->loadTasks();
    }
    public function refreshTask($taskId)
    {
        $task = Task::with(['priority', 'status', 'project', 'assignees', 'team'])->find($taskId);

        if ($task) {
            $this->tasks[$task->task_status_id] = $this->tasks[$task->task_status_id]->map(function ($t) use ($task) {
                return $t['id'] === $task->id ? $task->toArray() : $t;
            });
        }
    }
    public function loadTasks()
    {
        $query = Task::query()
            ->with(['priority', 'status', 'project', 'assignees', 'team'])
            ->whereHas('project.team', fn($q) => $q->where('id', $this->teamId))
            ->orderBy('task_status_id') // Ensure tasks are ordered by status first
            ->orderBy('board_position') // Then order tasks by board position
            ->get()
            ->groupBy('task_status_id')
            ->toArray();

        $this->tasks = $query;
    }


    public function updateTaskOrder($statusId, $taskOrder)
    {
        $tasks = Task::where('task_status_id', $statusId)
            ->whereHas('project.team', fn($q) => $q->where('id', $this->teamId))
            ->get()
            ->keyBy('id');

        foreach ($taskOrder as $index => $taskId) {
            if (isset($tasks[$taskId])) {
                $tasks[$taskId]->update(['board_position' => $index + 1]);
            }
        }

        $this->loadTasks();
        $this->dispatch('refreshBoard');
    }


    public function updateTaskStatus($taskId, $statusId, $position)
    {
        $task = Task::find($taskId);

        if ($task) {
            // Update the task's status and save
            $task->update(['task_status_id' => $statusId]);

            // Fetch all tasks with the new status, including the moved task
            $tasks = Task::where('task_status_id', $statusId)
                ->whereHas('project.team', fn($q) => $q->where('id', $this->teamId))
                ->orderBy('board_position')
                ->get();

            // Remove the moved task temporarily
            $tasks = $tasks->reject(fn($t) => $t->id == $task->id);

            // Insert the moved task into the correct position
            $tasks->splice($position, 0, [$task]);

            // Update board positions for all tasks
            foreach ($tasks as $index => $t) {
                $t->update(['board_position' => $index + 1]);
            }

            $this->loadTasks(); // Reload tasks to reflect changes
            $this->dispatch('refreshBoard'); // Refresh frontend dynamically
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
