<?php
namespace App\Http\Controllers;

use App\Models\Team;

class TeamController extends Controller
{
    public function ownTeams()
    {
        $teams = Team::where('user_id', auth()->id())->with('owner')->get(); // Filter by owner and include owner details
        return $teams;
    }

    public function showTeam($id)
    {
        $team = Team::where('id', $id) // Filter by the team ID

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

    public function dashboard(Team $team)
    {
        return view('teams.dashboard', compact('team'));
    }

    public function projects(Team $team)
    {
        $projects = $team->projects; // Assuming a `hasMany` relationship
        return view('teams.projects', compact('team', 'projects'));
    }

    public function members(Team $team)
    {
        $members = $team->members; // Assuming a `belongsToMany` relationship
        return view('teams.members', compact('team', 'members'));
    }




}
