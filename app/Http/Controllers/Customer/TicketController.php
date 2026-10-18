<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        $tickets = SupportTicket::with(['assignee'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('customer.tickets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:rental,sales,payment,general'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_staff' => false,
        ]);

        return redirect()->route('customer.tickets.show', $ticket)
            ->with('success', "Support inquiry #{$ticket->ticket_number} submitted. A concierge agent will respond promptly.");
    }

    public function show(SupportTicket $ticket): View
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['messages.user', 'assignee']);

        return view('customer.tickets.show', compact('ticket'));
    }

    public function reply(SupportTicket $ticket, Request $request): RedirectResponse
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:3000'],
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_staff' => false,
        ]);

        if ($ticket->status === 'resolved' || $ticket->status === 'closed') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Your reply has been posted.');
    }
}
