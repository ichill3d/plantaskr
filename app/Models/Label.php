<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'team_id',
    ];

    // Relationship with Team
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    // Many-to-Many Relationship with Tasks
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_label_relation', 'labels_id', 'tasks_id')
            ->withTimestamps();
    }
}

