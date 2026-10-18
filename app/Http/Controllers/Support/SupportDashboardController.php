<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SupportDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'all');
        $category = $request->input('category', 'all');

        $query = SupportTicket::with(['user', 'assignee', 'messages']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
        ];

        return view('support.dashboard', compact('tickets', 'stats', 'status', 'category'));
    }

    public function show(SupportTicket $ticket): View
    {
        $ticket->load(['user', 'assignee', 'messages.user']);

        return view('support.show', compact('ticket'));
    }

    public function reply(SupportTicket $ticket, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
            'status' => ['nullable', 'in:open,in_progress,resolved,closed'],
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_staff' => true,
        ]);

        $ticketUpdate = ['assigned_to' => Auth::id()];
        if (! empty($validated['status'])) {
            $ticketUpdate['status'] = $validated['status'];
        } elseif ($ticket->status === 'open') {
            $ticketUpdate['status'] = 'in_progress';
        }

        $ticket->update($ticketUpdate);

        return back()->with('success', 'Staff reply sent to customer.');
    }

    public function updateStatus(SupportTicket $ticket, Request $request): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
        ]);

        $update = ['status' => $request->input('status')];
        if ($request->has('priority')) {
            $update['priority'] = $request->input('priority');
        }

        $ticket->update($update);

        return back()->with('success', "Ticket #{$ticket->ticket_number} status updated.");
    }
}
