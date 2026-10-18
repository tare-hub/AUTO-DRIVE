@extends('layouts.dashboard')

@section('title', 'Customer Support Workspace')
@section('page_title', 'Support Tickets Queue')
@section('page_subtitle', 'Manage client service inquiries, airport concierge deliveries, and booking assistance')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-400">Open Tickets</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['open'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Unanswered inquiries</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-blue-400">In Progress</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['in_progress'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Staff replying</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-purple-400">Resolved</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['resolved'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Client assisted</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Closed</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['closed'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Archived conversations</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('support.dashboard') }}" class="glass-card rounded-2xl p-4 border border-white/10 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <select name="status" onchange="this.form.submit()"
                    class="bg-carbon-900 border border-white/15 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-electric-400">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>

            <select name="category" onchange="this.form.submit()"
                    class="bg-carbon-900 border border-white/15 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-electric-400">
                <option value="all" {{ $category === 'all' ? 'selected' : '' }}>All Categories</option>
                <option value="rental" {{ $category === 'rental' ? 'selected' : '' }}>Rental Logistics</option>
                <option value="sales" {{ $category === 'sales' ? 'selected' : '' }}>Vehicle Acquisitions</option>
                <option value="payment" {{ $category === 'payment' ? 'selected' : '' }}>Payments</option>
                <option value="general" {{ $category === 'general' ? 'selected' : '' }}>General</option>
            </select>
        </div>

        <span class="text-xs text-slate-400">Total: <strong class="text-white">{{ $tickets->total() }}</strong> inquiry ticket(s)</span>
    </form>

    <!-- Tickets Table -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                    <tr>
                        <th class="py-3 px-3">Ticket #</th>
                        <th class="py-3 px-3">Client</th>
                        <th class="py-3 px-3">Subject</th>
                        <th class="py-3 px-3">Category</th>
                        <th class="py-3 px-3">Priority</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Assigned To</th>
                        <th class="py-3 px-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($tickets as $t)
                        @php
                            $statusBadge = match($t->status) {
                                'open' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                'in_progress' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                'resolved' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                                default => 'bg-slate-700 text-slate-300 border-slate-600',
                            };
                            $priorityColor = match($t->priority) {
                                'urgent' => 'text-rose-400 font-bold',
                                'high' => 'text-amber-400 font-semibold',
                                default => 'text-slate-300',
                            };
                        @endphp
                        <tr class="hover:bg-white/5 transition align-top">
                            <td class="py-3.5 px-3 font-mono font-bold text-white">{{ $t->ticket_number }}</td>
                            <td class="py-3.5 px-3">
                                <div class="font-bold text-white">{{ $t->user?->name }}</div>
                                <span class="text-[10px] text-slate-400">{{ $t->user?->email }}</span>
                            </td>
                            <td class="py-3.5 px-3 font-bold text-slate-200">{{ $t->subject }}</td>
                            <td class="py-3.5 px-3 uppercase text-[10px] font-mono text-slate-400">{{ $t->category }}</td>
                            <td class="py-3.5 px-3 uppercase text-[10px] {{ $priorityColor }}">{{ $t->priority }}</td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                    {{ str_replace('_', ' ', $t->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-slate-300">{{ $t->assignee?->name ?: 'Unassigned' }}</td>
                            <td class="py-3.5 px-3 text-right">
                                <a href="{{ route('support.tickets.show', $t) }}"
                                   class="px-3 py-1.5 rounded-lg bg-carbon-800 hover:bg-carbon-700 text-electric-400 text-xs font-semibold border border-white/10 transition">
                                    Respond &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">No support tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-white/10">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
