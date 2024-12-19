<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'parent_type',
        'parent_id',
        'user_id',
        'content',
    ];

    // Polymorphic relationship for parent (Task/Project)
    public function parent()
    {
        return $this->morphTo();
    }
    public function reactions()
    {
        return $this->hasMany(CommentReaction::class, 'comments_id');
    }
    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
