<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'file_name',
        'file_path',
        'uploaded_by',
    ];

    // Relationship to tasks
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'attachment_task', 'attachment_id', 'task_id')->withTimestamps();
    }

    // Relationship to projects
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'attachment_project', 'attachment_id', 'project_id')->withTimestamps();
    }

    // Relationship to the user who uploaded the file
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
