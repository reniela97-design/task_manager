<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller {
    
    /**
     * Display a listing of users grouped by specific roles.
     */
    public function index() {
        // 1. Kuhaon tanan users uban ang ilang roles (apil soft-deleted)
        $allUsers = User::withTrashed()->with('role')->orderBy('created_at', 'desc')->get();
        
        // 2. I-filter ang tagsa-tagsa ka kategorya
        
        // Filter para sa ADMINISTRATOR
        $admins = $allUsers->filter(function($user) {
            return strtoupper($user->role->role_name ?? '') === 'ADMINISTRATOR';
        });

        // Filter para sa MANAGER
        $managers = $allUsers->filter(function($user) {
            return strtoupper($user->role->role_name ?? '') === 'MANAGER';
        });

        // CATCH-ALL LOGIC: Para sa USERS table
        // Bisan unsa nga role (Users, Staff, Member, etc.) mapunta diri basta dili Admin/Manager
        $regularUsers = $allUsers->filter(function($user) {
            $roleName = strtoupper($user->role->role_name ?? '');
            return $roleName !== 'ADMINISTRATOR' && $roleName !== 'MANAGER';
        });

        // 3. Kuhaon ang active roles para sa dropdown sa modals
        $roles = Role::where('role_inactive', 0)->get();
        
        return view('users', compact('admins', 'managers', 'regularUsers', 'roles', 'allUsers'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role_id' => 'required|exists:roles,id', 
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'user_log_datetime' => now(), 
            'user_inactive' => 0, 
        ]);

        return redirect()->route('users.index')->with('status', 'User created successfully.');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($request->only(['name', 'email', 'role_id']));

        return redirect()->route('users.index')->with('status', 'User updated.');
    }

    /**
     * Remove the specified user (Soft Delete).
     */
    public function destroy(User $user) {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete(); 
        return redirect()->route('users.index')->with('status', 'User archived.');
    }
}