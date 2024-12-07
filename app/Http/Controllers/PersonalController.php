<?php
namespace App\Http\Controllers;

use App\Models\Project;

class PersonalController extends Controller
{


    public function dashboard()
    {
        $user = auth()->user();
        $user->current_team_id = null;
        $user->save();
        return view('personal.dashboard');
    }

    public function projects()
    {
        $user = auth()->user();
        $projects = Project::where('user_id', $user->id)->get();
        return view('personal.dashboard', compact( 'projects'));
    }




}
