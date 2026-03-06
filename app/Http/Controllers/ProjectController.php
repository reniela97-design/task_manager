<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;


class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('client')->latest()->get();
        $clients = Client::all();
        return view('projects', compact('projects', 'clients'));
    }
// I-add ni sa sulod sa ProjectController class
public function start(Project $project)
{
    $project->update([
        'status' => 'In Progress',
        'start_date' => now()->format('Y-m-d'),
    ]);

    return back()->with('success', 'Project is now In Progress!');
}

public function complete(Project $project)
{
    $project->update([
        'status' => 'Completed',
        'completion_percent' => 100, // I-set nato og 100% diretso
    ]);

    return back()->with('success', 'Project marked as Completed!');
}
    public function store(Request $request)
    {
        // 1. I-validate ang tanan inputs apil ang priority ug start_date
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'branch'    => 'nullable|string',
            'address'   => 'nullable|string',
            'priority'  => 'required|in:Normal,Medium,High,Urgent',
            'start_date'=> 'nullable|date',
        ]);

        // 2. Logic: I-set ang Status base sa Start Date
        if (!empty($request->start_date)) {
            $validated['status'] = 'In Progress';
        } else {
            $validated['status'] = 'Pending';
        }

        // 3. I-save sa database
        Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function update(Request $request, Project $project)
    {
        // 1. Same validation sa store
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'branch'    => 'nullable|string',
            'address'   => 'nullable|string',
            'priority'  => 'required|in:Normal,Medium,High,Urgent',
            'start_date'=> 'nullable|date',
        ]);

        // 2. I-update ang Status kung gi-usab ang Start Date
        if (!empty($request->start_date)) {
            $validated['status'] = 'In Progress';
        } else {
            // Optional: Kung gusto nimo mobalik sa Pending kung gi-delete ang date
            $validated['status'] = 'Pending';
        }

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function show(Project $project)
    {
        return view('project-details', compact('project'));
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}