<?php


namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\User;

class TaskAssigneesEditable extends Component
{
    public Task $task;
    public $users;
    public $searchQuery = '';

    public function mount($taskId)
    {
        $this->task = Task::find($taskId);
        $this->users = $this->task->team->members->merge([$this->task->team->creator])->unique('id') ?? collect();
    }

    public function assignUser($userId)
    {
        if (!$this->task->assignees->contains($userId)) {
            $this->task->assignees()->attach($userId, ['role_id' => 2]);
            $this->task->refresh();
            $this->dispatch('assigneesUpdated');
            session()->flash('success', 'User assigned to the task successfully!');
        }
    }

    public function unassignUser($userId)
    {
        if ($this->task->assignees->contains($userId)) {
            $this->task->assignees()->detach($userId);
            $this->task->refresh();
            $this->dispatch('assigneesUpdated');
            session()->flash('success', 'User unassigned from the task successfully!');
        }
    }

    public function render()
    {
        return view('livewire.task-assignees-editable', [
            'filteredUsers' => $this->searchQuery
                ? $this->users->filter(fn($user) => str_contains(strtolower($user->name), strtolower($this->searchQuery)))
                : $this->users
        ]);
    }
}
