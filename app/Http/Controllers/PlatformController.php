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
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        // We use withoutGlobalScopes() to bypass the TenantScope
        // so the Super Admin can see data across all companies.
        
        $totalCompanies = Company::count();
        $totalUsers = User::count();
        $totalProducts = Product::withoutGlobalScopes()->count();
        
        $recentCompanies = Company::latest()->take(5)->get();

        return response()->json([
            'metrics' => [
                'total_companies' => $totalCompanies,
                'total_users' => $totalUsers,
                'total_products' => $totalProducts,
            ],
            'recent_companies' => $recentCompanies
        ]);
    }
}
