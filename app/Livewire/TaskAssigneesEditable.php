<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\User;

class TaskAssigneesEditable extends Component
{
    public Task $task;
    public $users = [];
    public $searchQuery = '';

    public function mount($taskId)
    {
        $this->task = Task::findOrFail($taskId);
        $this->loadUsers(); // Load team members initially when the dropdown is opened
    }

    public function loadUsers()
    {
        $this->users = $this->task->team->members
            ->merge([$this->task->team->creator])
            ->unique('id') ?? collect();
    }

    public function assignUser($userId)
    {
        if (!$this->task->assignees->contains($userId)) {
            $this->task->assignees()->attach($userId, ['role_id' => 2]);
            $this->task->refresh();
            $this->dispatch('reloadTask', ['taskId' => $this->task->id]);
            $this->dispatch('reloadTaskSidebar', ['taskId' => $this->task->id]);
        }
    }

    public function unassignUser($userId)
    {
        if ($this->task->assignees->contains($userId)) {
            $this->task->assignees()->detach($userId);
            $this->task->refresh();
            $this->dispatch('reloadTask', ['taskId' => $this->task->id]);
            $this->dispatch('reloadTaskSidebar', ['taskId' => $this->task->id]);

        }
    }

    public function render()
    {
        return view('livewire.task-assignees-editable', [
            'filteredUsers' => $this->searchQuery
                ? collect($this->users)->filter(fn($user) => str_contains(strtolower($user->name), strtolower($this->searchQuery)))
                : $this->users
        ]);
    }
}
