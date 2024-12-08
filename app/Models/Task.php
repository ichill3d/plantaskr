<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'task_status_id',
        'task_priorities_id',
        'name',
        'description',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
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
            ->withPivot('role_id') // Include the role_id in the pivot
            ->withTimestamps();
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
}
