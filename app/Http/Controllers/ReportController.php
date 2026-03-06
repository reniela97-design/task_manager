<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use App\Http\Controllers\Controller; // Siguradoa nga naa ni

class ReportController extends Controller
{
    public function index()
    {
        // 1. PRODUCTIVITY REPORT: Kuhaon ang mga COMPLETED tasks
        $completed_tasks = Task::where('status', 'Completed') 
            ->orderBy('updated_at', 'desc') 
            ->get()
            ->map(function ($task) {
                // FIX: Gigamit ang 'started_at' ug 'finished_at'
                if ($task->started_at && $task->finished_at) {
                    $start = Carbon::parse($task->started_at);
                    $end = Carbon::parse($task->finished_at);
                    
                    // Duration calculation
                    $task->duration_formatted = $start->diff($end)->format('%Hh %Im %Ss');

                    // Overdue calculation
                    if ($task->deadline) {
                        $deadline = Carbon::parse($task->deadline);
                        $task->is_overdue = $end->gt($deadline);

                        if ($task->is_overdue) {
                            $task->late_duration = $deadline->diff($end)->format('%dd %Hh %Im');
                        }
                    }
                } else {
                    $task->duration_formatted = '00h 00m 00s';
                }
                
                return $task;
            });

        // 2. AGING REPORT: Kuhaon ang mga PENDING tasks
        $pending_tasks = Task::where('status', '!=', 'Completed')
            ->orderBy('created_at', 'asc')
            ->get();

        // 3. I-pass ang data padulong sa view
        return view('reports', compact('completed_tasks', 'pending_tasks'));
    }
}