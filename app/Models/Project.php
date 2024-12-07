<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'team_id'];

    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'projects_id', 'users_id')
            ->withPivot('project_roles_id')
            ->withTimestamps();
    }

    public function statusRelation()
    {
        return $this->hasOne(ProjectStatusRelation::class, 'projects_id');
    }



}
