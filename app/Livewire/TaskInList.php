<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskInList extends Component
{
    public Task $task;
    public array $columns;
    public $enabledColumnsCount;
    public string $lastColumn;

    protected $listeners = [
        'reloadTask' => 'reloadIfMatchingTask',
        'refreshTaskList' => 'reloadAllTasks',
        'updateTaskInListColumns' => 'updateColumns',
    ];

    public function mount(Task $task, array $columns, string $lastColumn = '')
    {
        $this->task = $task;
        $this->columns = $columns;
        $this->lastColumn = $lastColumn;
    }
    public function updateColumns($updatedColumnsData)
    {
        $this->columns = $updatedColumnsData['updatedColumns'];
        $this->lastColumn = $updatedColumnsData['lastColumn'];
        $this->enabledColumnsCount = $updatedColumnsData['enabledColumnsCount'];
        $this->dispatch('$refresh'); // Ensures the child re-renders with new columns
    }
    public function reloadIfMatchingTask($data)
    {

        if ($data['taskId'] == $this->task->id) {
            logger('refreshed:', ['data' => $data]);
            $this->task->refresh();
        }
    }
    public function reloadAllTasks()
    {
        logger('refreshed all tasks');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        return view('livewire.task-in-list');
    }
}
