<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ProjectStatusRelation;

class ProjectController extends Controller
{
    private function getNavItems($organizationId, $organizationAlias, Project $project): array
    {
        return [
            [
                'label' => __('Overview'),
                'route' => route('organizations.projects.show', [
                    'id' => $organizationId,
                    'organization_alias' => $organizationAlias,
                    'project_id' => $project->id
                ]),
                'active' => 'projects.show',
            ],
            [
                'label' => __('Discussion'),
                'route' => route('organizations.projects.discussion', [
                    'id' => $organizationId,
                    'organization_alias' => $organizationAlias,
                    'project_id' => $project->id
                ]),
                'active' => 'projects.discussion',
            ],
            [
                'label' => __('Tasks'),
                'route' => route('organizations.projects.tasks', [
                    'id' => $organizationId,
                    'organization_alias' => $organizationAlias,
                    'project_id' => $project->id
                ]),
                'active' => 'projects.tasks',
            ],
            [
                'label' => __('Milestones'),
                'route' => route('organizations.projects.milestones', [
                    'id' => $organizationId,
                    'organization_alias' => $organizationAlias,
                    'project_id' => $project->id
                ]),
                'active' => 'projects.milestones',
            ],
            [
                'label' => __('Members'),
                'route' => route('organizations.projects.members', [
                    'id' => $organizationId,
                    'organization_alias' => $organizationAlias,
                    'project_id' => $project->id
                ]),
                'active' => 'projects.members',
            ],
        ];
    }

    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $teams = auth()->user()->ownedTeams()->get();
        return view('projects.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'team_id' => 'nullable|exists:teams,id', // Validate team_id if provided
        ]);

        $project = Project::create($validated);

        // Add the authenticated user as the project owner (role = 1)
        $project->users()->attach(auth()->id(), [
            'project_roles_id' => 1, // Role ID for owner
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add a row in project_status_relation
        ProjectStatusRelation::create([
            'projects_id' => $project->id,
            'project_status_id' => 1,
        ]);

        $team = Team::where('id', $validated['team_id'])->first();

        $org_id = $team?->id;
        $org_alias = $team?->alias; // Assuming the Team model has an alias column
        $project_id = $project->id;

        return redirect()->route('organizations.projects.show', [
            'id' => $org_id,
            'organization_alias' => $org_alias,
            'project_id' => $project_id,
        ])->with('success', 'Project created successfully.');
    }

    public function show($id, $organizationAlias, $projectId, $tab = 'overview')
    {
        // Retrieve the team
        $team = Team::where('id', $id)->firstOrFail();

        // Ensure the team alias matches the route parameter
        if ($team->alias !== $organizationAlias) {
            return redirect()->route('organizations.projects.show', [
                'id' => $team->id,
                'organizationAlias' => $team->alias,
                'projectId' => $projectId,
            ], 301);
        }

        // Retrieve the project
        $project = Project::where('id', $projectId)->where('team_id', $team->id)->firstOrFail();

        $allowedTabs = ['overview', 'discussion', 'tasks', 'milestones', 'members', 'settings', 'files', 'board'];
        if (!in_array($tab, $allowedTabs)) {
            abort(404); // Invalid tab, return a 404 response
        }

        return view('projects.show', compact('project', 'team', 'tab'));
    }


    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function discussion($id, $organizationAlias, $projectId)
    {
        $project = Project::where('id', $projectId)->firstOrFail();

        $navItems = $this->getNavItems($id, $organizationAlias, $project);
        return view('projects.discussion', compact('project', 'navItems'));
    }

    public function tasks($id, $organizationAlias, $projectId)
    {
        $project = Project::where('id', $projectId)->firstOrFail();

        $navItems = $this->getNavItems($id, $organizationAlias, $project);
        return view('projects.tasks', compact('project', 'navItems'));
    }
}
