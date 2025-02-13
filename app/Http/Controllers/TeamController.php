<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;

class TeamController extends Controller
{

    public function showTeam($id)
    {
        $team = Team::where('id', $id)->firstOrFail();
        return view('teams.show', compact('team'));
    }

    public function overview($id, $organization_alias)
    {
        $team = Team::where('id', $id)->firstOrFail();

        auth()->user()->update(['current_team_id' => $team->id]);

        // Redirect to the correct URL if the alias doesn't match
        if ($team->alias !== $organization_alias) {

            return redirect()->route('organizations.overview', [
                'id' => $team->id,
                'organization_alias' => $team->alias,
            ], 301);
        }

        return view('teams.overview', compact('team'));
    }

    public function projects($id, $organization_alias)
    {
        $team = Team::where('id', $id)->firstOrFail();



        // Redirect to the correct URL if the alias doesn't match
        if ($team->alias !== $organization_alias) {
            return redirect()->route('organizations.projects', [
                'id' => $team->id,
                'organization_alias' => $team->alias,
            ], 301);
        }

        $projects = $team->projects()->get();

        return view('teams.projects', compact('team', 'projects'));
    }

    public function members($id, $organization_alias)
    {
        $team = Team::where('id', $id)->firstOrFail();

        // Redirect to the correct URL if the alias doesn't match
        if ($team->alias !== $organization_alias) {
            return redirect()->route('organizations.members', [
                'id' => $team->id,
                'organization_alias' => $team->alias,
            ], 301);
        }

        $members = $team->members;
        return view('teams.members', compact('team', 'members'));
    }
     public function tasks($id, $organization_alias, $task_id = null)
    {
        $team = Team::find( $id);
        $selectedTask = $task_id ? Task::find($task_id) : null;

        // Redirect to the correct URL if the alias doesn't match
        if ($team->alias !== $organization_alias) {
            return redirect()->route('organizations.members', [
                'id' => $team->id,
                'organization_alias' => $team->alias,
            ], 301);
        }

        $tasks = $team->tasks;
        return view('teams.tasks', compact('team', 'tasks', 'selectedTask'));
    }
public function board($id, $organization_alias, $task_id = null)
    {
        $team = Team::find( $id);
        $selectedTask = $task_id ? Task::find($task_id) : null;
        // Redirect to the correct URL if the alias doesn't match
        if ($team->alias !== $organization_alias) {
            return redirect()->route('organizations.members', [
                'id' => $team->id,
                'organization_alias' => $team->alias,
            ], 301);
        }
        return view('teams.board', compact('team', 'selectedTask'));
    }

    public function membersManagement($id, $organization_alias)
    {
        $team = Team::where('id', $id)->firstOrFail();

        // Redirect to the correct URL if the alias doesn't match
        if ($team->alias !== $organization_alias) {
            return redirect()->route('organizations.members', [
                'id' => $team->id,
                'organization_alias' => $team->alias,
            ], 301);
        }
        return view('teams.members-management', compact('team'));
    }
    public function settings($id, $organization_alias)
    {
        $team = Team::where('id', $id)->firstOrFail();

        // Redirect to the correct URL if the alias doesn't match
        if ($team->alias !== $organization_alias) {
            return redirect()->route('organizations.members', [
                'id' => $team->id,
                'organization_alias' => $team->alias,
            ], 301);
        }
        return view('teams.settings', compact('team'));
    }

}
