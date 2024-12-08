<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskStatus;
use App\Models\TaskPriority;
use App\Models\User;
use App\Models\TaskRole;

class TaskController extends Controller
{
    public function index($taskListType = null)
    {
        $currentTeamId = auth()->user()->current_team_id;

        // Determine which tasks to fetch based on the task list type
        $tasks = Task::with(['project', 'status', 'priority', 'users'])
            ->when($taskListType === 'project', function ($query) use ($currentTeamId) {
                $query->whereHas('project', function ($query) use ($currentTeamId) {
                    $query->where('team_id', $currentTeamId); // Tasks from projects belonging to the current team
                });
            })
            ->when($taskListType === 'team', function ($query) use ($currentTeamId) {
                $query->whereHas('project', function ($query) use ($currentTeamId) {
                    $query->where('team_id', $currentTeamId); // Tasks connected to the current team
                });
            })
            ->get();

        return view('tasks.index', compact('tasks'));
    }
    public function show(Task $task)
    {
        $task->load(['project', 'status', 'priority', 'users', 'labels', 'links.type', 'attachments']);

        return view('tasks.show', compact('task'));
    }
    public function create()
    {
        $currentTeamId = auth()->user()->current_team_id;

        $projects = $currentTeamId
            ? Project::where('team_id', $currentTeamId)->get()
            : Project::all();

        $statuses = TaskStatus::all();
        $priorities = TaskPriority::all();
        $users = $currentTeamId
            ? User::whereHas('teams', function ($query) use ($currentTeamId) {
                $query->where('teams.id', $currentTeamId);
            })->get()
            : collect(); // Empty collection if no team
        $roles = TaskRole::all();

        return view('tasks.create', compact(
                                    'projects',
                                    'statuses',
                                    'priorities',
                                    'users',
                                    'currentTeamId',
                                    'roles')
        );
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'task_status_id' => 'required|exists:task_statuses,id',
            'task_priorities_id' => 'required|exists:task_priorities,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Create the task
        $task = Task::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'project_id' => $validated['project_id'],
            'task_status_id' => $validated['task_status_id'],
            'task_priorities_id' => $validated['task_priorities_id'],
        ]);

        // Assign the logged-in user as the author
        $syncData = [
            auth()->id() => ['role_id' => 1], // Author role
        ];

        // Add additional assigned users if provided
        if ($request->has('user_ids')) {
            foreach ($request->input('user_ids') as $userId) {
                $syncData[$userId] = ['role_id' => 2]; // Default to no specific role
            }
        }

        $task->users()->sync($syncData);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function getTasks(Request $request)
    {
        $team_id = $request->input('team_id') ?? auth()->user()->current_team_id;
        $user_id = $request->input('user_id') ?? auth()->id();
        $project_id = $request->input('project_id');

        // Base query for tasks
        $tasks = Task::select([
            'tasks.id',
            'tasks.name',
            'tasks.description',
            'tasks.project_id',
            'tasks.task_status_id',
            'tasks.created_at',
        ])
            ->with(['project.team', 'status', 'users']); // Add relationships

        // Filter by user_id (if provided)
        if (isset($user_id)) {
            $tasks->whereHas('users', function ($q) use ($user_id) {
                $q->where('users.id', $user_id);
            });
        }

        // Filter by team_id (if provided)
        if ($team_id) {
            $tasks->whereHas('project', function ($q) use ($team_id) {
                $q->where('team_id', $team_id);
            });
        }

        // Filter by project_id (if provided)
        if ($project_id) {
            $tasks->where('project_id', $project_id);
        }

        // Datatables processing
        $dataTable = datatables()->of($tasks)
            ->addColumn('action', function ($row) {
                return '<a href="/tasks/' . $row->id . '" class="text-blue-500 hover:text-blue-600 hover:underline transition">
                View
            </a>';
            })
            ->addColumn('linkedName', function ($row) {
                return '<a href="/tasks/' . $row->id . '" class="text-blue-500 hover:text-blue-600 hover:underline transition">
                ' . $row->name . '
            </a>';
            })
            ->addColumn('linkedProject', function ($row) {
                return '<a href="/projects/' . $row->project->id . '" class="text-purple-500 hover:text-purple-600 hover:underline transition">
                ' . $row->project->name . '
            </a>';
            })
            ->addColumn('linkedAssignees', function ($row) {
                return $row->users->map(function ($user) {
                    return '<a href="/users/' . $user->id . '" class="text-blue-500 hover:text-blue-600 hover:underline transition">
                    ' . $user->name . '
                </a>';
                })->implode(', ');
            })
            ->addColumn('taskStatus', function ($row) {
                return $row->status
                    ? '<span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-lg">' . $row->status->name . '</span>'
                    : '<span class="text-gray-400 italic">No Status</span>';
            })
            ->editColumn('created_at', function ($task) {
                return \Carbon\Carbon::parse($task->created_at)->format('d.m.Y H:i:s');
            })
            ->rawColumns(['action', 'linkedName', 'linkedProject', 'linkedAssignees', 'taskStatus']);

        return $dataTable->make(true);
    }





}
