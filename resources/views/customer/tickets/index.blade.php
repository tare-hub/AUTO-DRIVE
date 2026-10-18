@extends('layouts.dashboard')

@section('title', 'My Support Tickets')
@section('page_title', 'Customer Concierge Desk')
@section('page_subtitle', 'Direct communication with AutoDrive client service specialists')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="text-xs text-slate-400">
            Have a question about your booking, airport delivery, or acquisition? Open a direct ticket.
        </div>
        <a href="{{ route('customer.tickets.create') }}" class="px-4 py-2 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs shadow-md shadow-electric-500/25 transition">
            + Open New Ticket
        </a>
    </div>

    <div class="glass-card rounded-2xl p-6 border border-white/10 overflow-hidden">
        @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                        <tr>
                            <th class="py-3 px-4">Ticket #</th>
                            <th class="py-3 px-4">Subject</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Priority</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Last Updated</th>
                            <th class="py-3 px-4 text-right">Conversation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($tickets as $ticket)
                            @php
                                $statusBadge = match($ticket->status) {
                                    'open' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                    'in_progress' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                    'resolved' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                                    default => 'bg-slate-700 text-slate-300 border-slate-600',
                                };
                            @endphp
                            <tr class="hover:bg-white/5 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-white">{{ $ticket->ticket_number }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-200">{{ $ticket->subject }}</td>
                                <td class="py-3.5 px-4 text-slate-300 uppercase font-mono text-[10px]">{{ $ticket->category }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="text-[10px] uppercase font-semibold text-amber-400">{{ $ticket->priority }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                        {{ str_replace('_', ' ', $ticket->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-400 font-mono">{{ $ticket->updated_at->diffForHumans() }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('customer.tickets.show', $ticket) }}" class="px-3 py-1.5 rounded-lg bg-carbon-800 hover:bg-carbon-700 text-slate-200 border border-white/10 text-xs font-semibold transition">
                                        View Thread &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-white/10">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="text-center py-12 space-y-4">
                <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-slate-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">No Support Inquiries</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Have questions regarding billing, flight delivery, or vehicle options? Start a ticket with our support specialists.</p>
                <a href="{{ route('customer.tickets.create') }}" class="inline-block px-5 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs transition">
                    Create Support Ticket
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
