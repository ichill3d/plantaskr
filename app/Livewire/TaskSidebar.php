<?php

namespace App\Livewire;

use Livewire\Component;


class TaskSidebar extends Component
{
    public $task;

    protected $listeners = [
        'reloadTaskSidebar' => 'reloadIfMatchingTask',
    ];
    public function reloadIfMatchingTask($data)
    {

        if ($data['taskId'] == $this->task->id) {
            logger('refreshed:', ['data' => $data]);
            $this->task->refresh();
        }
    }
    public function render()
    {
        return view('livewire.task-sidebar');
    }
}
