<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReaction extends Model
{
    protected $fillable = [
        'comments_id',
        'reactions_types_id',
        'user_id'
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comments_id');
    }

    public function reactionType()
    {
        return $this->belongsTo(ReactionsType::class, 'reactions_types_id');
    }

}
