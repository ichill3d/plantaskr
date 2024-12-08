<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatusRelation extends Model
{
    use HasFactory;

    protected $fillable = ['projects_id', 'project_status_id', 'created_at', 'updated_at'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'projects_id');
    }

    public function status()
    {
        return $this->belongsTo(ProjectStatus::class, 'project_status_id');
    }
}
