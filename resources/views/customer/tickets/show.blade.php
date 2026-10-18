@extends('layouts.dashboard')

@section('title', "Ticket #{$ticket->ticket_number}")
@section('page_title', "Ticket #{$ticket->ticket_number}")
@section('page_subtitle', $ticket->subject)

@section('content')
<div class="max-w-4xl space-y-6">
    <!-- Ticket Meta Header Card -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                @php
                    $statusBadge = match($ticket->status) {
                        'open' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                        'in_progress' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                        'resolved' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                        default => 'bg-slate-700 text-slate-300 border-slate-600',
                    };
                @endphp
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                    {{ str_replace('_', ' ', $ticket->status) }}
                </span>
                <span class="text-xs uppercase font-mono px-2 py-0.5 rounded bg-white/5 border border-white/10 text-slate-300">
                    {{ $ticket->category }}
                </span>
            </div>
            <h2 class="text-xl font-bold text-white">{{ $ticket->subject }}</h2>
        </div>

        <div class="text-xs text-right text-slate-400">
            <div>Opened: <span class="text-slate-300 font-mono">{{ $ticket->created_at->format('M d, Y h:i A') }}</span></div>
            <div>Assigned Agent: <span class="text-electric-400 font-semibold">{{ $ticket->assignee?->name ?: 'Unassigned Concierge' }}</span></div>
        </div>
    </div>

    <!-- Messages Discussion Stream -->
    <div class="space-y-4">
        @foreach($ticket->messages as $msg)
            <div class="glass-card rounded-2xl p-6 border {{ $msg->is_staff ? 'border-electric-500/40 bg-electric-950/20' : 'border-white/10' }} space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-white/5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full {{ $msg->is_staff ? 'bg-electric-500 text-white' : 'bg-slate-700 text-slate-200' }} flex items-center justify-center font-bold text-xs">
                            {{ substr($msg->user?->name ?? 'User', 0, 2) }}
                        </div>
                        <div>
                            <span class="text-sm font-bold text-white">{{ $msg->user?->name }}</span>
                            @if($msg->is_staff)
                                <span class="ml-1.5 px-2 py-0.5 rounded bg-electric-500/30 text-electric-300 border border-electric-500/50 text-[10px] font-semibold">
                                    AutoDrive Concierge Staff
                                </span>
                            @endif
                        </div>
                    </div>
                    <span class="text-xs text-slate-400 font-mono">{{ $msg->created_at->format('M d, Y h:i A') }}</span>
                </div>

                <div class="text-sm text-slate-200 leading-relaxed whitespace-pre-line">
                    {{ $msg->message }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Reply Form -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
        <h3 class="text-base font-bold text-white">Post Reply</h3>
        <form method="POST" action="{{ route('customer.tickets.reply', $ticket) }}" class="space-y-4">
            @csrf
            <div>
                <textarea name="message" rows="4" required placeholder="Type your response to the support team..."
                          class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-electric-400"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs shadow-md shadow-electric-500/25 transition">
                    Send Reply
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
