<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizations = \App\Models\Organization::all(); // Fetch all organizations
        return view('organizations.index', compact('organizations')); // Return a view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create the organization
        $organization = \App\Models\Organization::create($validated);

        // Attach the current user as the owner (role_id = 1)
        $organization->users()->attach(auth()->id(), [
            'organizations_roles_id' => 1, // Role ID for owner
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('organizations.owned')->with('success', 'Organization created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organization = \App\Models\Organization::findOrFail($id); // Fetch by ID
        return view('organizations.show', compact('organization')); // Return a show view
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $organization = \App\Models\Organization::findOrFail($id); // Fetch by ID
        return view('organizations.edit', compact('organization')); // Return an edit form view
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $organization = \App\Models\Organization::findOrFail($id);
        $organization->update($validated); // Update the record

        return redirect()->route('organizations.owned')->with('success', 'Organization updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $organization = \App\Models\Organization::findOrFail($id);
        $organization->delete(); // Delete the record

        return redirect()->route('organizations.owned')->with('success', 'Organization deleted successfully!');
    }

    public function ownedOrganizations()
    {
        $user = auth()->user(); // Get the authenticated user
        $ownedOrganizations = $user->ownedOrganizations; // Fetch owned organizations
        return view('organizations.index', compact('ownedOrganizations'));
    }
}
