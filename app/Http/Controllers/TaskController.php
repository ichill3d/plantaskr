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
                $syncData[$userId] = ['role_id' => null]; // Default to no specific role
            }
        }

        $task->users()->sync($syncData);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }


}
