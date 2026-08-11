<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AuditLog;

class BillingController extends Controller
{
    public function index()
    {
        return Inertia::render('Billing');
    }

    public function pricing()
    {
        return Inertia::render('Pricing');
    }

    public function upgrade(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:free,basic,pro'
        ]);

        $company = auth()->user()->company;
        
        $mrr = 0;
        if ($request->plan === 'basic') $mrr = 4900.00;
        if ($request->plan === 'pro') $mrr = 9900.00;

        $company->update([
            'subscription_tier' => $request->plan,
            'mrr' => $mrr
        ]);

        AuditLog::record('Subscription Plan Updated', "Changed subscription plan to " . ucfirst($request->plan) . ".");

        return redirect()->back()->with('success', 'Your subscription plan has been updated successfully!');
    }
}
