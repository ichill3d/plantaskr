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
            ->orderBy('task_status_id') // Ensure tasks are ordered by status first
            ->orderBy('board_position') // Then order tasks by board position
            ->get()
            ->groupBy('task_status_id')
            ->toArray();

        $this->tasks = $query;
    }


    public function updateTaskOrder($statusId, $taskOrder)
    {
        // Ensure tasks belong to the correct team and status
        $tasks = Task::where('task_status_id', $statusId)
            ->whereHas('project.team', fn($q) => $q->where('id', $this->teamId))
            ->get()
            ->keyBy('id');

        // Reorder tasks based on the received taskOrder
        foreach ($taskOrder as $index => $taskId) {
            if (isset($tasks[$taskId])) {
                $tasks[$taskId]->update(['board_position' => $index + 1]);
            }
        }

        // Reload tasks to reflect updated order
        $this->loadTasks();
    }

    public function updateTaskStatus($taskId, $statusId, $position)
    {

        $task = Task::find($taskId);

        if ($task) {
            // Update the task status
            $task->task_status_id = $statusId;
            $task->save();
            // Fetch tasks in the same status and reorder
            $tasks = Task::where('task_status_id', $statusId)
                ->whereHas('project.team', fn($q) => $q->where('id', $this->teamId))
                ->orderBy('board_position')
                ->get();

            // Remove the moved task from the list
            $tasks = $tasks->reject(fn($t) => $t->id == $task->id);

            // Insert the moved task into the desired position
            $tasks->splice($position, 0, [$task]);

            // Update board_position for all tasks
            foreach ($tasks as $index => $t) {
                $t->update(['board_position' => $index + 1]);
            }

            $this->loadTasks(); // Reload tasks to reflect changes
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
