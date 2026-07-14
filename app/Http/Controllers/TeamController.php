<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TeamController extends Controller
{
    /**
     * Display a listing of the team members.
     */
    public function index()
    {
        // The TenantScope automatically filters users by the current user's company_id
        $users = User::all();
        
        return Inertia::render('Roles', [
            'team' => $users
        ]);
    }

    /**
     * Store a newly created team member in storage.
     */
    public function store(Request $request)
    {
        // Only admins can invite/create team members
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Only admins can manage the team.'], 403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', Rule::in(['manager', 'staff'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // Default password for newly created accounts
            'role' => $request->role,
            'company_id' => Auth::user()->company_id,
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Team member added successfully. Default password is "password".',
            'user' => $user
        ], 201);
    }

    /**
     * Update the specified team member's role.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Only admins can manage the team.'], 403);
        }

        $user = User::where('company_id', Auth::user()->company_id)->findOrFail($id);

        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'You cannot edit your own role.'], 400);
        }

        $request->validate([
            'role' => ['required', 'string', Rule::in(['manager', 'staff'])],
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        $user->syncRoles([$request->role]);

        return response()->json(['message' => 'Team member updated successfully.']);
    }

    /**
     * Remove the specified team member.
     */
    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Only admins can manage the team.'], 403);
        }

        $user = User::where('company_id', Auth::user()->company_id)->findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'You cannot remove yourself from the team.'], 400);
        }

        $user->delete();

        return response()->json(['message' => 'Team member removed successfully.']);
    }
}
