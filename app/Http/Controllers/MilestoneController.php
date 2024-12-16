<?php
namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index($id, $organization_alias, Project $project)
    {
        $milestones = Milestone::where('projects_id', $project->id)->get();
        return view('milestones.index', compact('milestones', 'project', 'id', 'organization_alias'));
    }

    public function create($id, $organization_alias, Project $project)
    {
        return view('milestones.create', compact('project', 'id', 'organization_alias'));
    }

    public function store(Request $request, $id, $organization_alias, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        Milestone::create([
            'projects_id' => $project->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('milestones.index', [$id, $organization_alias, $project])
            ->with('success', 'Milestone created successfully.');
    }

    public function edit($id, $organization_alias, Project $project, Milestone $milestone)
    {
        return view('milestones.edit', compact('milestone', 'project', 'id', 'organization_alias'));
    }

    public function update(Request $request, $id, $organization_alias, Project $project, Milestone $milestone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $milestone->update($validated);

        return redirect()->route('milestones.index', [$id, $organization_alias, $project])
            ->with('success', 'Milestone updated successfully.');
    }

    public function destroy($id, $organization_alias, Project $project, Milestone $milestone)
    {
        $milestone->delete();

        return redirect()->route('milestones.index', [$id, $organization_alias, $project])
            ->with('success', 'Milestone deleted successfully.');
    }
}
