<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class RoleController extends Controller
{
    /**
     * Display the list of roles.
     */
    public function index()
    {
        // 1. Kuhaa ang data gikan sa database
        $roles = Role::all(); 

        // FIX: Usba ang 'reports' ngadto sa 'roles' 
        // para mugamit sa roles.blade.php nga naay @extends('layouts.app')
        return view('roles', compact('roles'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name',
        ]);

        Role::create([
            'role_name' => $request->role_name,
            'role_inactive' => 0, // Default to active
        ]);

        return redirect()->route('roles.index')->with('status', 'Role created successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        $hardcodedRoles = ['Administrator', 'Manager', 'User'];

        if (in_array($role->role_name, $hardcodedRoles)) {
            return redirect()->back()->with('error', 'Action Denied: You cannot delete core System Roles.');
        }

        // Siguroa nga naa kay users() relationship sa imong Role model
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete role: There are active users assigned to this role.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('status', 'Role deleted successfully.');
    }
}