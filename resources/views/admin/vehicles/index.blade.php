@extends('layouts.dashboard')

@section('title', 'Fleet Inventory Management')
@section('page_title', 'Fleet Inventory Registry')
@section('page_subtitle', 'Manage all registered exotic and luxury automobiles, pricing, and availability states')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Search and Filters -->
        <form method="GET" action="{{ route('admin.vehicles.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search brand, model, VIN..."
                   class="bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-electric-400">

            <select name="category_id" onchange="this.form.submit()"
                    class="bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()"
                    class="bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                <option value="">All Statuses</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>Rented</option>
                <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>

            <button type="submit" class="px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 text-xs font-semibold">
                Filter
            </button>
        </form>

        <a href="{{ route('admin.vehicles.create') }}" class="px-5 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs shadow-lg shadow-electric-500/25 flex items-center gap-1.5 transition">
            + Add New Vehicle
        </a>
    </div>

    <!-- Vehicles Fleet Table -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                    <tr>
                        <th class="py-3 px-4">Vehicle</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Plate & VIN</th>
                        <th class="py-3 px-4">Daily Rate / Sale</th>
                        <th class="py-3 px-4">Listing</th>
                        <th class="py-3 px-4">Status & Quick Toggle</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($vehicles as $v)
                        <tr class="hover:bg-white/5 transition align-middle">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <img src="{{ $v->main_image }}" alt="{{ $v->full_name }}" class="w-12 h-10 object-cover rounded-lg border border-white/10 shrink-0">
                                <div>
                                    <div class="font-bold text-white text-sm">{{ $v->full_name }}</div>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ number_format($v->mileage) }} mi &bull; {{ $v->transmission }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-300 font-medium">{{ $v->category?->name }}</td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-300">
                                <div>Plate: <strong class="text-white">{{ $v->license_plate }}</strong></div>
                                <span class="text-slate-500 text-[10px]">VIN: {{ $v->vin }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono">
                                <div class="text-white font-bold">${{ number_format($v->daily_rate, 2) }}/d</div>
                                @if($v->selling_price)
                                    <div class="text-electric-400 text-[11px]">${{ number_format($v->selling_price, 2) }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 uppercase text-[10px] font-bold text-slate-400">
                                {{ $v->listing_type }}
                            </td>
                            <td class="py-3.5 px-4">
                                <form method="POST" action="{{ route('admin.vehicles.status', $v) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="bg-carbon-900 border border-white/20 rounded-lg px-2.5 py-1 text-[11px] font-semibold text-white focus:outline-none">
                                        <option value="available" {{ $v->status === 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="rented" {{ $v->status === 'rented' ? 'selected' : '' }}>Rented</option>
                                        <option value="reserved" {{ $v->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                                        <option value="sold" {{ $v->status === 'sold' ? 'selected' : '' }}>Sold</option>
                                        <option value="maintenance" {{ $v->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('vehicles.show', $v) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 text-xs">
                                    View
                                </a>
                                <a href="{{ route('admin.vehicles.edit', $v) }}" class="px-2.5 py-1 rounded-lg bg-carbon-800 hover:bg-carbon-700 text-electric-400 text-xs font-semibold">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.vehicles.destroy', $v) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this vehicle from the fleet?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-950/80 hover:bg-rose-900 text-rose-300 text-xs">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">No vehicles match current criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-white/10">
            {{ $vehicles->links() }}
        </div>
    </div>
</div>
@endsection
