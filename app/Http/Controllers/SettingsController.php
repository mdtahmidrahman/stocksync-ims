<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $company = Company::find(Auth::user()->company_id);

        return Inertia::render('Settings', [
            'company' => $company
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|max:50',
            // Other settings can be added here later
        ]);

        $company = Company::find(Auth::user()->company_id);
        $company->update([
            'currency' => $request->currency,
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
