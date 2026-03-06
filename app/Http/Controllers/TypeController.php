<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return view('types', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate(['type_name' => 'required|string|max:255']);

        Type::create([
            'type_name' => $request->type_name,
            'type_user_id' => auth()->id(),
            'type_log_datetime' => now(),
            'type_inactive' => 0,
        ]);

        return redirect()->route('types.index')->with('status', 'Type added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['type_name' => 'required|string|max:255']);
        
        $type = Type::where('type_id', $id)->firstOrFail();
        
        $type->update([
            'type_name' => $request->type_name,
            'type_inactive' => $request->has('type_inactive') ? 1 : 0,
        ]);

        return redirect()->route('types.index')->with('status', 'Type updated.');
    }

    public function destroy($id)
    {
        Type::where('type_id', $id)->delete();
        return redirect()->route('types.index')->with('status', 'Type deleted.');
    }
}