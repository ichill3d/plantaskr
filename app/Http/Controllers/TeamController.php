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
                'route' => route('organizations.overview', ['id' => $team->id, 'organization_alias' => $team->alias]),
                'active' => 'organizations.overview',
            ],
            [
                'label' => __('Projects'),
                'route' => route('organizations.projects', ['id' => $team->id, 'organization_alias' => $team->alias]),
                'active' => 'organizations.projects',
            ],
            [
                'label' => __('Members'),
                'route' => route('organizations.members', ['id' => $team->id, 'organization_alias' => $team->alias]),
                'active' => 'organizations.members',
            ],
        ];
    }

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



        $navItems = $this->getNavItems($team);
        return view('teams.overview', compact('team', 'navItems'));
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

        $projects = $team->projects;
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
        $navItems = $this->getNavItems($team);
        return view('teams.members', compact('team', 'members', 'navItems'));
    }
}
