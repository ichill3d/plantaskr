<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // Import the Str helper
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  array<string, string>  $input
     */
    public function create(User $user, array $input): Team
    {

        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createTeam');

        AddingTeam::dispatch($user);

        $user->switchTeam($team = $user->ownedTeams()->create([
            'name' => $input['name'],
            'alias' => $this->generateUniqueAlias($input['name']),
            'personal_team' => false,
        ]));

        return $team;
    }

    /**
     * Generate a unique alias for the team.
     *
     * @param  string  $name
     * @return string
     */
    protected function generateUniqueAlias($name)
    {
        // Slugify the name
        $baseAlias = Str::slug($name);
        $alias = $baseAlias;
        $counter = 1;

        // Check for uniqueness and append a counter if needed
        while (Team::where('alias', $alias)->exists()) {
            $alias = "{$baseAlias}-{$counter}";
            $counter++;
        }

        return $alias;
    }
}
