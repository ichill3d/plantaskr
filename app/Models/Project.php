<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'team_id', 'color'];

    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'projects_id', 'users_id')
            ->withPivot('project_roles_id')
            ->withTimestamps();
    }

    // Relationship with tasks
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }


    // Relationship with milestones
    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'projects_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function statusRelation()
    {
        return $this->hasOne(ProjectStatusRelation::class, 'projects_id');
    }

    // Accessor for tasks count
    public function getTasksCountAttribute()
    {
        return $this->tasks()->count();
    }

    // Accessor for milestones count
    public function getMilestonesCountAttribute()
    {
        return $this->milestones()->count();
    }

    // Accessor for users count
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }



}
