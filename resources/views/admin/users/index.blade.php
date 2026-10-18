@extends('layouts.dashboard')

@section('title', 'User & Staff Management')
@section('page_title', 'Access & Role Governance')
@section('page_subtitle', 'Manage system operators, role privileges, client profiles, and account statuses')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Search and Filters -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..."
                   class="bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-electric-400">

            <select name="role" onchange="this.form.submit()"
                    class="bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                <option value="rental_manager" {{ request('role') === 'rental_manager' ? 'selected' : '' }}>Rental Manager</option>
                <option value="sales_manager" {{ request('role') === 'sales_manager' ? 'selected' : '' }}>Sales Manager</option>
                <option value="customer_support" {{ request('role') === 'customer_support' ? 'selected' : '' }}>Customer Support</option>
                <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
            </select>

            <select name="status" onchange="this.form.submit()"
                    class="bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>

            <button type="submit" class="px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 text-xs font-semibold">
                Filter
            </button>
        </form>

        <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs shadow-lg shadow-electric-500/25 flex items-center gap-1.5 transition">
            + Add New User / Staff
        </a>
    </div>

    <!-- Users Table -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                    <tr>
                        <th class="py-3 px-4">User</th>
                        <th class="py-3 px-4">Role Privileges</th>
                        <th class="py-3 px-4">Contact Phone</th>
                        <th class="py-3 px-4">Driver License</th>
                        <th class="py-3 px-4">Account Status</th>
                        <th class="py-3 px-4">Registered</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                        @php
                            $roleBadgeColor = match($user->role) {
                                'admin' => 'bg-purple-900/60 text-purple-300 border-purple-700/50',
                                'rental_manager' => 'bg-blue-900/60 text-blue-300 border-blue-700/50',
                                'sales_manager' => 'bg-amber-900/60 text-amber-300 border-amber-700/50',
                                'customer_support' => 'bg-teal-900/60 text-teal-300 border-teal-700/50',
                                default => 'bg-slate-800 text-slate-300 border-slate-700'
                            };
                        @endphp
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-white text-sm">{{ $user->name }}</div>
                                <span class="text-slate-400 text-xs">{{ $user->email }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider border {{ $roleBadgeColor }}">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-300">{{ $user->phone ?: 'N/A' }}</td>
                            <td class="py-3.5 px-4 font-mono text-slate-400">{{ $user->driver_license_number ?: 'Not on file' }}</td>
                            <td class="py-3.5 px-4">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Suspended
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-400">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="px-2.5 py-1 rounded-lg bg-carbon-800 hover:bg-carbon-700 text-electric-400 text-xs font-semibold">
                                    Edit
                                </a>

                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.status', $user) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg {{ $user->status === 'active' ? 'bg-amber-950/80 hover:bg-amber-900 text-amber-300' : 'bg-emerald-950/80 hover:bg-emerald-900 text-emerald-300' }} text-xs">
                                            {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-white/10">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
