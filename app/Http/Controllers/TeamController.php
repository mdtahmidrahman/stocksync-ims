<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Http\Requests\StoreTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;

class TeamController extends Controller
{
    /**
     * Display a listing of the team members.
     */
    public function index()
    {
        // The TenantScope automatically filters users by the current user's company_id
        $users = User::all()->map(function ($user) {
            $user->last_login_human = $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never logged in';
            return $user;
        });
        
        return Inertia::render('Roles', [
            'team' => $users
        ]);
    }

    /**
     * Store a newly created team member in storage.
     */
    public function store(StoreTeamMemberRequest $request)
    {
        // Only admins can invite/create team members
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized. Only admins can manage the team.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt(Str::random(16)),
            'company_id' => Auth::user()->company_id,
            'role' => $request->role,
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Team member added successfully.');
    }

    /**
     * Update the specified team member's role.
     */
    public function update(UpdateTeamMemberRequest $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized. Only admins can manage the team.');
        }

        $user = User::where('company_id', Auth::user()->company_id)->findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot edit your own role.');
        }

        $user->update([
            'role' => $request->role,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'Team member updated successfully.');
    }

    /**
     * Remove the specified team member.
     */
    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized. Only admins can manage the team.');
        }

        $user = User::where('company_id', Auth::user()->company_id)->findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Team member removed successfully.');
    }
}
