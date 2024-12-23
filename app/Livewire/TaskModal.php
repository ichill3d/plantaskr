<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskModal extends Component
{
    public $task;

    protected $listeners = [
        'openTaskModal' => 'loadTask',
        'closeTaskModal' => 'close'
    ];

    /**
     * Lifecycle Method
     */
    public function mount($taskId = null)
    {
        if ($taskId) {
            $this->task = Task::findOrFail($taskId);
            $this->dispatch('modal-opened');
        }
    }

    /**
     * Load Task for Modal
     */
    public function loadTask($taskId)
    {
        $this->task = Task::findOrFail($taskId);
        $this->dispatch('modal-opened'); // Notify Alpine.js to open the modal
        logger("Task Modal opened for Task ID: $taskId");
    }

    /**
     * Close the Modal
     */
    public function close()
    {
        $this->task = null; // Clear the task
        $this->dispatch('modal-closed');
        logger("Modal closed");
    }

    /**
     * Render the Modal Component
     */
    public function render()
    {
        return view('livewire.task-modal');
    }
}
