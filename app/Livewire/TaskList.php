<?php

namespace App\Livewire;

use App\Models\Milestone;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use App\Models\Team;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class TaskList extends Component
{
    use WithPagination;

    public $teamId;
    public $projectId;
    public $milestoneId;
    public $assigneeId;

    // Add these missing properties
    public $selectedProjects = [];
    public $selectedPriorities = [];
    public $selectedAssignees = [];
    public $selectedStatuses = [];
    public $selectedMilestones = [];

    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }
    public function toggleAssignee($assigneeId)
    {
        if (in_array($assigneeId, $this->selectedAssignees)) {
            $this->selectedAssignees = array_filter($this->selectedAssignees, fn($id) => $id !== $assigneeId);
        } else {
            $this->selectedAssignees[] = $assigneeId;
        }
    }

    public function toggleStatus($statusId)
    {
        if (in_array($statusId, $this->selectedStatuses)) {
            $this->selectedStatuses = array_filter($this->selectedStatuses, fn($id) => $id !== $statusId);
        } else {
            $this->selectedStatuses[] = $statusId;
        }
    }

    public function toggleMilestone($milestoneId)
    {
        if (in_array($milestoneId, $this->selectedMilestones)) {
            $this->selectedMilestones = array_filter($this->selectedMilestones, fn($id) => $id !== $milestoneId);
        } else {
            $this->selectedMilestones[] = $milestoneId;
        }
    }
    public function toggleProject($projectId)
    {
        if (in_array($projectId, $this->selectedProjects)) {
            // If the project is already selected, remove it
            $this->selectedProjects = array_filter($this->selectedProjects, fn ($id) => $id !== $projectId);
        } else {
            // Otherwise, add it to the selection
            $this->selectedProjects[] = $projectId;
        }
    }
    public function togglePriority($priorityId)
    {
        if (in_array($priorityId, $this->selectedPriorities)) {
            // Remove the priority if it’s already selected
            $this->selectedPriorities = array_filter($this->selectedPriorities, fn ($id) => $id !== $priorityId);
        } else {
            // Add the priority if it’s not selected
            $this->selectedPriorities[] = $priorityId;
        }

    }

    public function deleteTask($taskId)
    {
        Task::findOrFail($taskId)->delete();
    }
    public function render()
    {
        $tasks = Task::query()
            ->when($this->teamId, function ($query) {
                $query->whereHas('project.team', fn ($teamQuery) => $teamQuery->where('id', $this->teamId));
            })
            ->when($this->projectId, fn ($query) => $query->where('project_id', $this->projectId))
            ->when($this->milestoneId, fn ($query) => $query->where('milestone_id', $this->milestoneId))
            ->when(!empty($this->selectedProjects), fn ($query) => $query->whereIn('project_id', $this->selectedProjects))
            ->when(!empty($this->selectedPriorities), fn ($query) => $query->whereIn('task_priorities_id', $this->selectedPriorities))
            ->when(!empty($this->selectedAssignees), fn($query) =>
            $query->whereHas('assignees', fn($q) => $q->whereIn('users.id', $this->selectedAssignees))
            )
            ->when(!empty($this->selectedStatuses), fn($query) => $query->whereIn('task_status_id', $this->selectedStatuses))
            ->when(!empty($this->selectedMilestones), fn($query) => $query->whereIn('milestone_id', $this->selectedMilestones))
            ->when($this->sortColumn === 'task_priorities_id', function ($query) {
                $query->join('task_priorities', 'tasks.task_priorities_id', '=', 'task_priorities.id')
                    ->select('tasks.*')
                    ->orderBy('task_priorities.name', $this->sortDirection);
            }, fn ($query) => $query->orderBy($this->sortColumn ?? 'name', $this->sortDirection))
            ->with([
                'project:id,name',
                'priority:id,name',
                'assignees:id,name'
            ])
            ->paginate(10);


        if(!is_null($this->projectId)) {
            $project = Project::find($this->projectId);
            $team = $project->team;
        } else {
            $team = Team::find($this->teamId);
        }


        $projects = $team->projects()->limit(10)->get();
        $priorities = TaskPriority::limit(10)->get();
        $members = $team->members()->limit(10)->get();
        $creator = $team->creator()->select(['id', 'name', 'profile_photo_path'])->first();
        $assignees = $members->contains($creator) ? $members : $members->push($creator);
        $statuses = TaskStatus::limit(10)->get(); // Replace with your status model

        if (!is_null($this->projectId)) {
            $project = Project::find($this->projectId);
            $milestones = $project->milestones()->limit(10)->get();
        } else {
            $milestones = collect(); // Use an empty collection instead of an empty array
        }

        return view('livewire.task-list', compact('tasks', 'projects', 'priorities', 'assignees', 'statuses', 'milestones'));

    }


}
