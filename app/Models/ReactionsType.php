<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReactionsType extends Model
{
    protected $fillable = ['name', 'shortcode'];

    public function reactions()
    {
        return $this->hasMany(CommentReaction::class, 'reactions_types_id');
    }
}
