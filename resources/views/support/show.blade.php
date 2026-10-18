@extends('layouts.dashboard')

@section('title', "Support Ticket #{$ticket->ticket_number}")
@section('page_title', "Ticket #{$ticket->ticket_number}")
@section('page_subtitle', "Customer Conversation & Inquiry Resolution")

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Conversation Stream (Left 2 cols) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Ticket Header Card -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-3">
            <div class="flex items-center gap-2">
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
                    Category: {{ $ticket->category }}
                </span>
                <span class="text-xs uppercase font-mono px-2 py-0.5 rounded bg-amber-500/10 text-amber-300 border border-amber-500/30">
                    Priority: {{ $ticket->priority }}
                </span>
            </div>
            <h2 class="text-2xl font-bold text-white">{{ $ticket->subject }}</h2>
        </div>

        <!-- Messages Thread -->
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
                                        Staff Specialist
                                    </span>
                                @else
                                    <span class="ml-1.5 text-xs text-slate-400">Customer</span>
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

        <!-- Staff Reply Form -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
            <h3 class="text-base font-bold text-white">Send Official Staff Response</h3>
            <form method="POST" action="{{ route('support.tickets.reply', $ticket) }}" class="space-y-4">
                @csrf
                <div>
                    <textarea name="message" rows="4" required placeholder="Type professional response to client..."
                              class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-electric-400"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-3">
                        <label class="text-xs text-slate-300 font-semibold uppercase">Update Status to:</label>
                        <select name="status" class="bg-carbon-900 border border-white/15 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-electric-400">
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs shadow-md shadow-electric-500/25 transition">
                        Dispatch Response
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Client Profile & Quick Status (Right Column) -->
    <div class="space-y-6">
        <!-- Client Profile -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
            <h3 class="text-base font-bold text-white border-b border-white/10 pb-3">Authorized Client Info</h3>
            <div class="space-y-2 text-xs">
                <div>
                    <span class="text-slate-400 block">Name:</span>
                    <span class="text-white font-bold">{{ $ticket->user?->name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Email:</span>
                    <span class="text-slate-200">{{ $ticket->user?->email }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Phone:</span>
                    <span class="text-slate-200 font-mono">{{ $ticket->user?->phone ?: 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Driver License:</span>
                    <span class="text-slate-200 font-mono">{{ $ticket->user?->driver_license_number ?: 'Not on file' }}</span>
                </div>
                @if($ticket->user?->address)
                    <div>
                        <span class="text-slate-400 block">Address:</span>
                        <span class="text-slate-300">{{ $ticket->user->address }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Status / Priority Control -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
            <h3 class="text-base font-bold text-white border-b border-white/10 pb-3">Ticket Parameters</h3>
            <form method="POST" action="{{ route('support.tickets.status', $ticket) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Status</label>
                    <select name="status" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Priority</label>
                    <select name="priority" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                        <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2 rounded-xl bg-white/10 hover:bg-white/15 text-white font-semibold text-xs border border-white/15 transition">
                    Update Ticket Parameters
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
