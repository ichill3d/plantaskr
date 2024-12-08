<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * Relationship: Statuses linked to projects.
     */
    public function statusRelations()
    {
        return $this->hasMany(ProjectStatusRelation::class, 'project_status_id');
    }
}
