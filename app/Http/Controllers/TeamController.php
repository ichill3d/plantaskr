<?php
namespace App\Http\Controllers;

use App\Models\Team;

class TeamController extends Controller
{
    private function getNavItems(Team $team): array
    {
        return [
            [
                'label' => __('Overview'),
                'route' => route('organizations.overview', $team->id),
                'active' => 'organizations.overview',
            ],
            [
                'label' => __('Projects'),
                'route' => route('organizations.projects', $team->id),
                'active' => 'organizations.projects',
            ],
            [
                'label' => __('Members'),
                'route' => route('organizations.members', $team->id),
                'active' => 'organizations.members',
            ],
        ];
    }
    public function ownTeams()
    {
        $teams = Team::where('user_id', auth()->id())->with('owner')->get(); // Filter by owner and include owner details
        return $teams;
    }

    public function showTeam($id)
    {
        $team = Team::where('id', $id)
            ->firstOrFail(); // Retrieve the team or throw a 404 error
        return view('teams.show', compact('team'));
    }



    public function memberTeams()
    {
        $userId = auth()->id();

        $teams = Team::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId); // User is in team_user
        })->where('user_id', '!=', $userId) // User is not the owner
        ->with('owner') // Optionally eager load the owner
        ->get();
        return $teams;
    }
    public function listUserTeams()
    {
        $ownTeams = $this->ownTeams();
        $memberTeams = $this->memberTeams();
        return view('teams.index', compact('ownTeams', 'memberTeams'));
    }

    public function overview(Team $team)
    {
        $user = auth()->user();
        $user->current_team_id = $team->id;
        $user->save();
        $navItems = $this->getNavItems($team);
        return view('teams.overview', compact('team',  'navItems'));
    }

    public function projects(Team $team)
    {
        $projects = $team->projects; // Assuming a `hasMany` relationship
        $navItems = $this->getNavItems($team);
        return view('teams.projects', compact('team', 'projects','navItems'));
    }

    public function members(Team $team)
    {
        $members = $team->members; // Assuming a `belongsToMany` relationship
        $navItems = $this->getNavItems($team);
        return view('teams.members', compact('team', 'members',  'navItems'));
    }




}
