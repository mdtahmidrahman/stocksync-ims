<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('event', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('module')) {
            $query->where('event', 'like', $request->module . '%');
        }

        if ($request->filled('action')) {
            $query->where('event', 'like', '%' . $request->action);
        }

        if ($request->filled('date_start')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_start)->startOfDay());
        }

        if ($request->filled('date_end')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_end)->endOfDay());
        }

        $logs = $query->latest()->paginate(50)->withQueryString();
        $users = User::all(['id', 'name']);

        return Inertia::render('AuditLog', [
            'logs' => $logs,
            'users' => $users,
            'filters' => $request->only(['search', 'user_id', 'module', 'action', 'date_start', 'date_end']),
        ]);
    }
}
