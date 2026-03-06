<?php

namespace App\Http\Controllers; // Dapat Controllers, dili Models

use App\Models\ActivityLog; // Tawagon nato ang Model
use Illuminate\Http\Request;

class ActivityLogController extends Controller // Dapat extends Controller, dili Model
{
    public function index()
    {
        // Kuhaon ang logs gikan sa database
        $logs = ActivityLog::with('user')->latest()->paginate(15); 
        
        // Ipasa ang $logs padung sa view
        return view('logs', compact('logs'));
    }
}