<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLink extends Model
{
    protected $fillable = ['task_id', 'linked_task_id', 'task_link_type_id'];

    public function type()
    {
        return $this->belongsTo(TaskLinkType::class, 'task_link_type_id');
    }
}
