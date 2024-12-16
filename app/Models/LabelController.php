<?php
namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Team;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Display a listing of the labels.
     */
    public function index(Team $team)
    {
// Fetch labels specific to the team
        $labels = Label::where('team_id', $team->id)->get();

        return view('labels.index', compact('labels', 'team'));
    }

    /**
     * Show the form for creating a new label.
     */
    public function create(Team $team)
    {
        return view('labels.create', compact('team'));
    }

    /**
     * Store a newly created label in storage.
     */
    public function store(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'color' => 'required|string|max:255',
        ]);

        Label::create(array_merge($validated, [
            'team_id' => $team->id, // Associate the label with the current team
        ]));

        return redirect()->route('labels.index', $team)->with('success', 'Label created successfully.');
    }

    /**
     * Show the form for editing the specified label.
     */
    public function edit(Team $team, Label $label)
    {
// Ensure the label belongs to the current team
        abort_if($label->team_id !== $team->id, 403);

        return view('labels.edit', compact('label', 'team'));
    }

    /**
     * Update the specified label in storage.
     */
    public function update(Request $request, Team $team, Label $label)
    {
// Ensure the label belongs to the current team
        abort_if($label->team_id !== $team->id, 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'color' => 'required|string|max:255',
        ]);

        $label->update($validated);

        return redirect()->route('labels.index', $team)->with('success', 'Label updated successfully.');
    }

    /**
     * Remove the specified label from storage.
     */
    public function destroy(Team $team, Label $label)
    {
// Ensure the label belongs to the current team
        abort_if($label->team_id !== $team->id, 403);

        $label->delete();

        return redirect()->route('labels.index', $team)->with('success', 'Label deleted successfully.');
    }
}
