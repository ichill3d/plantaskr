<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $table = 'milestones';

    protected $fillable = [
        'projects_id',
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    // Relationship: A milestone belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class, 'projects_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }

}
