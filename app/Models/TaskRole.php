<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relationship with task_users
    public function users()
    {
        return $this->hasMany(TaskUser::class, 'role_id');
    }
}
