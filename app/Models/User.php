<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $casts = [
        'task_columns' => 'array', // Automatically cast JSON to an array
    ];



    public function organizationTeams()
    {
        return $this->teams()->where('personal_team', false); // Filter non-personal teams
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user', 'users_id', 'projects_id')
            ->withPivot('project_roles_id')
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->belongsToMany(
            Task::class,
            'task_users', // Pivot table name
            'users_id',   // Foreign key on the pivot table for users
            'tasks_id'    // Foreign key on the pivot table for tasks
        )->withPivot('role_id') // Include additional pivot data
        ->withTimestamps();   // Include timestamps
    }

    public function getAvatarAttribute()
    {
        if (!empty($this->profile_photo_path)) {
            logger('Using profile photo:', [$this->profile_photo_path]);
            return '<img src="' . e(asset('storage/' . $this->profile_photo_path)) . '" alt="Avatar" class="rounded-full w-10 h-10 object-cover">';
        }

        $names = explode(' ', $this->name);
        $initials = strtoupper(substr($names[0] ?? '', 0, 1) . substr(end($names) ?? '', 0, 1));

        logger('Using initials:', [$initials]);

        return '<div class="rounded-full bg-blue-500 text-white flex items-center justify-center w-10 h-10 text-sm font-bold">' . $initials . '</div>';
    }
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        // Fallback to a placeholder image
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0D8ABC&color=fff';
    }

}
