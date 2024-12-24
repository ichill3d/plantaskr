<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskInList extends Component
{
    public Task $task;
    public array $columns;
    public string $lastColumn;

    protected $listeners = [
        'reloadTask' => 'reloadIfMatchingTask',
    ];

    public function mount(Task $task, array $columns, string $lastColumn = '')
    {
        $this->task = $task;
        $this->columns = $columns;
        $this->lastColumn = $lastColumn;
    }

    public function reloadIfMatchingTask($data)
    {

        if ($data['taskId'] == $this->task->id) {
            logger('refreshed:', ['data' => $data]);
            $this->task->refresh();
        }
    }

    public function render()
    {
        return view('livewire.task-in-list');
    }
}
