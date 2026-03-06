<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    public function index()
    {
        $systems = System::all();
        return view('systems', compact('systems'));
    }

    public function store(Request $request)
    {
        $request->validate(['system_name' => 'required|string|max:255']);

        System::create([
            'system_name' => $request->system_name,
            'system_user_id' => auth()->id(),
            'system_log_datetime' => now(),
            'system_inactive' => 0,
        ]);

        return redirect()->route('systems.index')->with('status', 'System added successfully.');
    }

    // ADD THE UPDATE METHOD
    public function update(Request $request, $id)
    {
        $request->validate([
            'system_name' => 'required|string|max:255'
        ]);

        // Find using custom primary key
        $system = System::where('system_id', $id)->firstOrFail();

        $system->update([
            'system_name' => $request->system_name,
            // Handle checkbox for inactive status
            'system_inactive' => $request->has('system_inactive') ? 1 : 0,
        ]);

        return redirect()->route('systems.index')->with('status', 'System updated successfully.');
    }

    public function destroy($id)
    {
        // Find using custom primary key to avoid errors
        $system = System::where('system_id', $id)->firstOrFail();
        $system->delete();
        return redirect()->route('systems.index')->with('status', 'System deleted.');
    }
}