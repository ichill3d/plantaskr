<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;

class PersonalController extends Controller
{


    public function dashboard()
    {
        return view('personal.dashboard');
    }
    public function profile()
    {
        return view('personal.profile');
    }

    public function createOrganization()
    {
        return view('personal.create-organization');
    }

    public function organizations()
        {
            $userId = auth()->id();
            $ownTeams = Team::where('user_id', $userId)->with('owner')->get();
            $memberTeams = Team::whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->where('user_id', '!=', $userId)
                ->with('owner')
                ->get();
            return view('personal.organizations', compact('ownTeams', 'memberTeams'));
        }

    public function projects()
    {
        $user = auth()->user();
        $projects = Project::where('user_id', $user->id)->get();
        return view('personal.dashboard', compact( 'projects'));
    }




}
