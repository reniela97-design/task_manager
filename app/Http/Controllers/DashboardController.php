<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; 
use Carbon\Carbon;   

class DashboardController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now();
        $daysInMonth = $currentDate->daysInMonth; 
        
        $startOfMonth = Carbon::now()->startOfMonth();
        $emptySlots = $startOfMonth->dayOfWeek; 

        // KUHAON ANG MGA TASKS - Gidugangan nato og filter sa status
        // Ang 'Open' ra ang ipakita para kung ma-delete o mahuman, mawala sa calendar
        $tasks = Task::whereMonth('task_date', $currentDate->month)
                     ->whereYear('task_date', $currentDate->year)
                     ->where('status', 'Open') // Mao ni ang mag-limpyo sa imong calendar boss
                     ->orderBy('task_date', 'asc') 
                     ->get();

        // I-GROUP ANG TASKS BY DAY
        $calendarTasks = [];
        foreach($tasks as $task) {
            $day = $task->task_date->day; 
            $calendarTasks[$day][] = $task;
        }

        // UPDATED STATS
        $stats = [
            'total'   => Task::count(),
            'pending' => Task::where('status', 'Open')->count(),
            'urgent'  => Task::where('priority', 'Urgent')
                             ->where('status', 'Open') // Urgent nga wala pa nahuman
                             ->count(),
            'uptime'  => '99.9%' 
        ];

        return view('dashboard', compact(
            'daysInMonth', 
            'emptySlots', 
            'calendarTasks', 
            'stats', 
            'currentDate'
        ));
    }
}