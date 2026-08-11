<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warehouse;
use App\Models\AuditLog;
use App\Mail\UserInvitationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
        // The TenantScope automatically filters users and warehouses by company_id
        $users = User::all()->map(function ($user) {
            $user->last_login_human = $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never logged in';
            return $user;
        });

        $warehouses = Warehouse::select('id', 'name')->get();
        
        return Inertia::render('Roles', [
            'team' => $users,
            'warehouses' => $warehouses,
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

        $rawPassword = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($rawPassword),
            'company_id' => Auth::user()->company_id,
            'role' => $request->role,
            'location' => $request->location ?? 'All Locations',
        ]);

        $user->assignRole($request->role);

        // Send Invitation Email via SMTP
        try {
            $company = Auth::user()->company;
            $companyName = $company ? $company->name : 'StockSync IMS';
            Mail::to($user->email)->send(new UserInvitationMail(
                $user, 
                $companyName, 
                url('/login'), 
                $rawPassword
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send SMTP invite email: ' . $e->getMessage());
        }

        AuditLog::record(
            'Team Member Added',
            "Added team member '{$user->name}' ({$user->email}) with role '{$user->role}' at location '{$user->location}'."
        );

        return redirect()->back()->with('success', 'Team member invited successfully. Invitation sent to email.');
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

        $oldRole = ucfirst($user->role);
        $newRole = ucfirst($request->role);

        $user->update([
            'role' => $request->role,
            'location' => $request->location ?? $user->location ?? 'All Locations',
        ]);

        $user->syncRoles([$request->role]);

        AuditLog::record(
            'Role Changed',
            "Role for team member '{$user->name}' ({$user->email}) changed from '{$oldRole}' to '{$newRole}'."
        );

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

        $name = $user->name;
        $email = $user->email;

        $user->delete();

        AuditLog::record(
            'Team Member Removed',
            "Removed team member '{$name}' ({$email}) from the company."
        );

        return redirect()->back()->with('success', 'Team member removed successfully.');
    }
}
