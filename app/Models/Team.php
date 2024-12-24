<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
        'alias'
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'team_id');
    }
    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Project::class, 'team_id', 'project_id', 'id', 'id');
    }
    public function labels()
    {
        return $this->hasMany(Label::class, 'team_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id')
            ->withTimestamps();
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function allMembers()
    {
        return $this->members()
            ->when($this->creator, function ($query) {
                $query->orWhere('users.id', $this->creator->id);
            });
    }



}
