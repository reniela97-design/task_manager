<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\Category;
use App\Models\Type;
use App\Models\Role;
use App\Models\ActivityLog; 
use App\Notifications\TaskAssigned; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::whereNull('finished_at')
                     ->orderBy('task_date', 'asc') 
                     ->get();
        
        $stats = [
            'total' => Task::whereNull('finished_at')->count(),
            'urgent' => Task::where('priority', 'High')->whereNull('finished_at')->count(),
            'active' => Task::where('status', 'In Progress')->count(),
            'finished_today' => Task::whereDate('finished_at', today())->count(),
        ];
        
        $projects = Project::all();
        $clients = Client::all();
        $users = User::all();
        $roles = Role::all();
        $categories = Category::where('category_inactive', false)->get();
        $types = Type::where('type_inactive', false)->get();

        return view('tasks', compact('tasks', 'projects', 'clients', 'users', 'categories', 'types', 'roles', 'stats'));
    }

    public function start(Task $task)
    {
        $task->update([
            'started_at' => now(),
            'status' => 'In Progress'
        ]);
        return back()->with('success', 'Task started.');
    }

    public function finish(Task $task)
    {
        $task->update([
            'finished_at' => now(),
            'status' => 'Completed'
        ]);
        return back()->with('success', 'Task finished.');
    }

    public function store(Request $request)
    {
        // GI-REMOVE ANG authorizeAccess() DRE ARON MAKASULOD ANG TANAN
        
        $request->validate([
            'title'       => 'required|string|max:255',
            'user_id'     => 'required|exists:users,id',
            'project_id'  => 'required',
            'client_id'   => 'required',
            'task_date'   => 'required|date',
        ]);

        // LOGIC: Kung dili Admin/Manager, i-force ang user_id sa iyang kaugalingon
        $finalUserId = $request->user_id;
        $user = Auth::user();
        if (!$user->role || !in_array($user->role->role_name, ['Administrator', 'Manager'])) {
            $finalUserId = $user->id;
        }

        $task = Task::create([
            'title'       => $request->title,
            'description' => $request->description ?? 'No description provided',
            'task_date'   => $request->task_date,
            'status'      => $request->status ?? 'Open',
            'priority'    => $request->priority,
            'category'    => $request->category ?? 'General',
            'type'        => $request->type ?? 'Standard',
            'user_id'     => $finalUserId, // Gamiton ang finalUserId
            'project_id'  => $request->project_id,
            'client_id'   => $request->client_id,
        ]);

        try {
            $assignedUser = User::find($finalUserId);
            if ($assignedUser) {
                $assignedUser->notify(new TaskAssigned($task));
            }
        } catch (\Exception $e) {
            Log::error("Notification Error: " . $e->getMessage());
        }

        ActivityLog::record('Created new task: ' . $task->title, 'Tasks');

        return redirect()->back()->with('success', 'Task Created and User Notified!');
    }

    public function update(Request $request, Task $task)
    {
        // Pabilin nga Admin/Manager ra ang maka-edit
        $this->authorizeAccess();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'project_id'  => 'required|exists:projects,id',
            'client_id'   => 'required|exists:clients,id',
            'user_id'     => 'nullable|exists:users,id',
            'task_date'   => 'required|date',
            'status'      => 'required|string',
            'priority'    => 'nullable|string',
            'category'    => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $task->update($validated);

        if ($task->wasChanged('user_id')) {
            try {
                $newUser = User::find($task->user_id);
                if ($newUser) {
                    $newUser->notify(new TaskAssigned($task));
                }
            } catch (\Exception $e) {
                Log::error("Update Notification Failed: " . $e->getMessage());
            }
        }

        ActivityLog::record('Updated task: ' . $task->title, 'Tasks');

        return redirect()->route('tasks.index')->with('status', 'Task Updated!');
    }

    public function destroy(Task $task)
    {
        // Pabilin nga Admin/Manager ra ang maka-delete
        $this->authorizeAccess();
        $task->delete();
        ActivityLog::record('Archived task: ' . $task->title, 'Tasks');
        return redirect()->route('tasks.index')->with('status', 'Task archived!');
    }

    private function authorizeAccess()
    {
        $user = Auth::user();
        if (!$user->role || !in_array($user->role->role_name, ['Administrator', 'Manager'])) {
            abort(403, 'Unauthorized Action.');
        }
    }
}