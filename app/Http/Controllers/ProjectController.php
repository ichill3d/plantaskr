<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ProjectStatusRelation;



class ProjectController extends Controller
{
    private function getNavItems(Project $project): array
    {
        return [
            [
                'label' => __('Overview'),
                'route' => route('projects.show', $project->id),
                'active' => 'projects.show',
            ],
            [
                'label' => __('Discussion'),
                'route' => route('projects.discussion', $project->id),
                'active' => 'projects.projects',
            ],
            [
                'label' => __('Tasks'),
                'route' => route('projects.tasks', $project->id),
                'active' => 'projects.tasks',
            ],
            [
                'label' => __('Milestones'),
                'route' => route('projects.milestones', $project->id),
                'active' => 'projects.milestones',
            ],
            [
                'label' => __('Members'),
                'route' => route('projects.members', $project->id),
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



        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $navItems = $this->getNavItems($project);
        return view('projects.show', compact('project',  'navItems'));
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

    // Method to provide data for DataTables
    public function getProjects(Request $request)
    {
        if (empty($request->except(['draw', '_', '_token', 'start', 'length']))) {
            $team_id = auth()->user()->current_team_id; // Default team
        } else {
            // Parse the request data
            $user_id = $request->has('user_id')
                ? $request->input('user_id')
                : auth()->id();

            $team_id = $request->has('team_id')
                ? $request->input('team_id')
                : auth()->user()->current_team_id;
        }

        $projects = Project::select([
            'id',
            'name',
            'description',
            'team_id',
            'created_at'
        ])
        ->with(['users', 'statusRelation.status', 'team']); // Use find() for a specific ID



        if (isset($user_id)) {
            $projects->whereHas('users', function ($q) use ($user_id) {
                $q->where('users.id', $user_id);
            });
        }

        if ($team_id) {
            $projects->where('team_id', $team_id);
        }

        $includeTeamColumn = !$team_id;

//        $projects = $projects->get(); // Execute the query
//        dd($projects->toArray());

        $dataTable = datatables()->of($projects)
            ->addColumn('action', function ($row) {
                return '<a href="/projects/' . $row->id . '" class="text-blue-500 hover:text-blue-600 hover:underline transition">
                    View
                </a>';
            })
            ->addColumn('linkedName', function ($row) {
                return '<a href="/projects/' . $row->id . '" class="text-blue-500 hover:text-blue-600 hover:underline transition">
                    ' . $row->name . '
                </a>';
            })
            ->addColumn('linkedOwner', function ($row) {
                $owner = $row->users->firstWhere('pivot.project_roles_id', 1);
                return $owner
                    ? '<a href="/user/' . $owner->id . '" class="text-blue-500 hover:text-blue-600 hover:underline transition">
                    ' . $owner->name . '
                </a>'
                    : '<span class="text-gray-400 italic">No Owner</span>';
            })
            ->addColumn('projectStatus', function ($row) {
                $status = $row->statusRelation?->status;
                return $status
                    ? '<span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-lg">' . $status->name . '</span>'
                    : '<span class="text-gray-400 italic">No Status</span>';
            })

            ->editColumn('created_at', function ($project) {
                return \Carbon\Carbon::parse($project->created_at)->format('d.m.Y H:i:s');
            })
            ->rawColumns(['action', 'linkedName', 'linkedOwner', 'projectStatus', 'team']);

        if (!$team_id) {
            $dataTable->addColumn('team', function ($row) {
                if ($row->team) {
                    return '<a href="/organizations/' . $row->team->id . '"
                       class="text-purple-500 hover:text-purple-600 hover:underline transition">
                        ' . $row->team->name . '
                    </a>';
                }
                return '<span class="text-gray-400 italic">[Personal Project]</span>';
            });
            $dataTable->rawColumns(['team']);
        }

        return $dataTable->make(true);

    }


    public function discussion(Project $project)
    {
        $navItems = $this->getNavItems($project);
        return view('projects.discussion', compact('project','navItems'));
    }

    public function tasks(Project $project)
    {
        $navItems = $this->getNavItems($project);
        return view('projects.tasks', compact('project', 'navItems'));
    }
}
