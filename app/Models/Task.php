<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'task_status_id',
        'task_priorities_id',
        'name',
        'description',
        'due_date',
        'created_by_user_id',
        'board_position'
    ];
    protected $appends = ['priority_color', 'is_overdue'];
    protected $casts = [
        'due_date' => 'date',
    ];
    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'parent');
    }
    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

    public function priority()
    {
        return $this->belongsTo(TaskPriority::class, 'task_priorities_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users', 'tasks_id', 'users_id')
            ->withPivot('role_id')
            ->withTimestamps();
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'task_label_relation', 'task_id', 'label_id');
    }

    public function links()
    {
        return $this->hasMany(TaskLink::class, 'task_id');
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class, 'task_id');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'task_status_id' => 'required|exists:task_status,id',
            'task_priorities_id' => 'required|exists:task_priorities,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $task = Task::create($validated);

        if ($request->has('user_ids')) {
            $task->users()->sync($request->input('user_ids'));
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }
    public function getPriorityColorAttribute()
    {
        $priorityName = $this->priority->name ?? null; // Fetch related priority name, or null if not set

        return match ($priorityName) {
            'Urgent' => '#D90000',
            'High' => '#FF8000',
            'Medium' => '#2DA8F2',
            'Low' => '#7ECE13',
            default => '#808080',
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date && Carbon::parse($this->due_date)->isPast();
    }
    public function assignees()
    {
        return $this->belongsToMany(
            User::class,
            'task_users',
            'tasks_id',
            'users_id'
        )->withPivot('role_id')->withTimestamps();
    }
    public function team()
    {
        return $this->hasOneThrough(Team::class, Project::class, 'id', 'id', 'project_id', 'team_id');
    }

}
