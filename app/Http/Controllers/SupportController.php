<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Super Admin sees tickets from ALL companies; Company users see their company's tickets
        if ($user && $user->isSuperAdmin()) {
            $tickets = SupportTicket::withoutGlobalScopes()->with(['user', 'replies'])->latest()->get();
        } else {
            $tickets = SupportTicket::with(['user', 'replies'])->latest()->get();
        }

        return Inertia::render('Support', [
            'tickets' => $tickets,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'priority' => 'nullable|string|max:50',
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::create([
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'priority' => $validated['priority'] ?? 'medium',
            'message' => $validated['message'],
            'status' => 'open',
        ]);

        AuditLog::record('Support Ticket Created', "Created support ticket #{$ticket->id}: '{$ticket->subject}'.");

        return redirect()->back()->with('success', 'Your support ticket has been submitted successfully.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        SupportTicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // If ticket was closed or resolved, set to in_progress on new reply
        if ($ticket->status === 'closed' || $ticket->status === 'resolved') {
            $ticket->update(['status' => 'in_progress']);
        }

        AuditLog::record('Support Ticket Replied', "Replied to support ticket #{$ticket->id}.");

        return redirect()->back()->with('success', 'Reply submitted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved,closed',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        AuditLog::record('Support Ticket Status Changed', "Ticket #{$ticket->id} status updated to '{$request->status}'.");

        return redirect()->back()->with('success', 'Ticket status updated.');
    }
}
