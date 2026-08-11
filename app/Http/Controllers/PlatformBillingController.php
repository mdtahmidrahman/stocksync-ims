<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Company;

class PlatformBillingController extends Controller
{
    public function index()
    {
        $companies = Company::where('subscription_tier', '!=', 'free')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalMrr = Company::sum('mrr');
        $activePaid = Company::where('subscription_tier', '!=', 'free')->count();

        return Inertia::render('Platform/Billing', [
            'companies' => $companies,
            'total_mrr' => $totalMrr,
            'active_paid' => $activePaid
        ]);
    }
}
