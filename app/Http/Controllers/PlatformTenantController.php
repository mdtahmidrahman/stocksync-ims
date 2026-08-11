<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PlatformTenantController extends Controller
{
    /**
     * List all tenants for Super Admin
     */
    public function index(Request $request)
    {
        $companies = Company::withCount('users')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return inertia('Platform/Tenants', [
            'companies' => $companies
        ]);
    }

    /**
     * Update tenant status
     */
    public function updateStatus(Request $request, Company $company)
    {
        $request->validate([
            'status' => 'required|in:active,past_due,cancelled,trialing'
        ]);

        $company->update(['subscription_status' => $request->status]);

        return redirect()->back()->with('success', 'Tenant status updated.');
    }

    /**
     * Update tenant subscription tier and MRR
     */
    public function updateTier(Request $request, Company $company)
    {
        $request->validate([
            'tier' => 'required|in:free,basic,pro',
            'mrr' => 'required|numeric|min:0'
        ]);

        $company->update([
            'subscription_tier' => $request->tier,
            'mrr' => $request->mrr
        ]);

        return redirect()->back()->with('success', 'Tenant subscription updated.');
    }

    /**
     * Impersonate a tenant admin
     */
    public function impersonate(Company $company)
    {
        \Log::info('Impersonate called by user: ' . Auth::id() . ' for company: ' . $company->id);
        if (!$company->allow_support_impersonation) {
            return redirect()->back()->with('error', 'This company has not granted support impersonation permission.');
        }

        $admin = User::where('company_id', $company->id)->where('role', 'admin')->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'No admin found for this company to impersonate.');
        }

        Session::put('impersonated_by', Auth::id());
        
        Auth::login($admin);
        Session::save();
        
        \Log::info('Session after login: ', Session::all());

        return redirect('/dashboard')->with('success', 'You are now impersonating ' . $admin->name . ' (' . $company->name . ')');
    }

    public function leaveImpersonation()
    {
        \Log::info('Leave impersonation called. Session has impersonated_by: ' . (Session::has('impersonated_by') ? 'yes' : 'no'));
        \Log::info('Current session data: ', Session::all());

        if (Session::has('impersonated_by')) {
            $superAdminId = Session::get('impersonated_by');
            \Log::info('Super Admin ID from session: ' . $superAdminId);
            
            $superAdmin = User::withoutGlobalScopes()->find($superAdminId);
            \Log::info('Did we find Super Admin? ' . ($superAdmin ? 'Yes, ID ' . $superAdmin->id : 'No'));
            
            if ($superAdmin) {
                Session::forget('impersonated_by');
                \Log::info('Forgot impersonated_by from session.');
                
                Auth::login($superAdmin);
                \Log::info('Logged in as super admin.');
                
                Session::save();
                \Log::info('Session saved.');
                
                \Log::info('Successfully logged back in as super admin. Redirecting to platform tenants.');
                return redirect('/platform/tenants')->with('success', 'Impersonation ended.');
            } else {
                Session::forget('impersonated_by');
                \Log::info('Super admin not found, redirecting to login.');
                return redirect('/login')->with('error', 'Super Admin not found.');
            }
        }

        \Log::info('No active impersonation session found. Redirecting back.');
        return redirect()->back()->with('error', 'No active impersonation session found.');
    }
}
