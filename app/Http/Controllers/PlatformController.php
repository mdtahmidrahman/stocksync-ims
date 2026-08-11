<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformController extends Controller
{
    /**
     * Get platform-wide analytics for the Super Admin dashboard.
     */
    public function dashboardMetrics()
    {
        // Ensure only Super Admins can access this
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // We use withoutGlobalScopes() to bypass the TenantScope
        // so the Super Admin can see data across all companies.
        
        $totalCompanies = Company::count();
        $totalUsers = User::count();
        $totalProducts = Product::withoutGlobalScopes()->count();
        $openTickets = \App\Models\SupportTicket::withoutGlobalScopes()->where('status', 'open')->count();
        
        // Financial & Subscription Data
        $totalMrr = Company::sum('mrr');
        $activeSubscriptions = Company::where('subscription_status', 'active')->count();
        
        $planDistribution = [
            'free' => Company::where('subscription_tier', 'free')->count(),
            'basic' => Company::where('subscription_tier', 'basic')->count(),
            'pro' => Company::where('subscription_tier', 'pro')->count(),
        ];

        // Authentic growth data for the chart (last 30 days)
        $thirtyDaysAgo = now()->subDays(29)->startOfDay();
        $companiesGrowth = Company::where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $labels = [];
        $series = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $series[] = $companiesGrowth->get($date) ?? 0;
        }

        $growthData = [
            'labels' => $labels,
            'series' => $series,
        ];
        
        $recentCompanies = Company::latest()->take(5)->get();

        return inertia('SuperAdminDashboard', [
            'metrics' => [
                'total_companies' => $totalCompanies,
                'total_users' => $totalUsers,
                'total_products' => $totalProducts,
                'open_tickets' => $openTickets,
                'total_mrr' => $totalMrr,
                'active_subscriptions' => $activeSubscriptions,
            ],
            'charts' => [
                'plan_distribution' => $planDistribution,
                'growth' => $growthData,
            ],
            'recent_companies' => $recentCompanies
        ]);
    }

    /**
     * Create a new company account & send admin invitation via SMTP.
     */
    public function storeCompany(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
        ]);

        $company = Company::create([
            'name' => $request->company_name,
        ]);

        $rawPassword = \Illuminate\Support\Str::random(10);

        $user = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => bcrypt($rawPassword),
            'company_id' => $company->id,
            'role' => 'admin',
            'location' => 'All Locations',
        ]);

        $user->assignRole('admin');

        // Send invitation email via SMTP
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserInvitationMail(
                $user,
                $company->name,
                url('/login'),
                $rawPassword
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send SMTP company invite email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "Company '{$company->name}' created and admin invitation sent via email.");
    }
}
