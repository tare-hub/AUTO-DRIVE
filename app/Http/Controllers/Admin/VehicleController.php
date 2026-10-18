<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vehicle::with('category');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('vin', 'like', "%{$search}%")
                    ->orWhere('license_plate', 'like', "%{$search}%");
            });
        }

        if ($cat = $request->input('category_id')) {
            $query->where('category_id', $cat);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($listing = $request->input('listing_type')) {
            $query->where('listing_type', $listing);
        }

        $vehicles = $query->latest()->paginate(10)->withQueryString();
        $categories = VehicleCategory::all();

        return view('admin.vehicles.index', compact('vehicles', 'categories'));
    }

    public function create(): View
    {
        $categories = VehicleCategory::where('is_active', true)->get();

        return view('admin.vehicles.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:vehicle_categories,id'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1990', 'max:2030'],
            'vin' => ['required', 'string', 'max:50', 'unique:vehicles,vin'],
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate'],
            'color' => ['nullable', 'string', 'max:50'],
            'transmission' => ['required', 'string', 'max:50'],
            'fuel_type' => ['required', 'string', 'max:50'],
            'seating_capacity' => ['required', 'integer', 'min:1', 'max:12'],
            'mileage' => ['required', 'integer', 'min:0'],
            'engine_capacity' => ['nullable', 'string', 'max:100'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'listing_type' => ['required', 'in:rental,sale,both'],
            'status' => ['required', 'in:available,rented,reserved,sold,maintenance'],
            'condition' => ['required', 'in:new,used,certified_preowned'],
            'description' => ['nullable', 'string'],
            'features_list' => ['nullable', 'string'],
            'main_image' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $features = [];
        if (! empty($validated['features_list'])) {
            $features = array_filter(array_map('trim', explode("\n", $validated['features_list'])));
        }

        $vehicle = Vehicle::create([
            'category_id' => $validated['category_id'],
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'vin' => $validated['vin'],
            'license_plate' => $validated['license_plate'],
            'color' => $validated['color'] ?? null,
            'transmission' => $validated['transmission'],
            'fuel_type' => $validated['fuel_type'],
            'seating_capacity' => $validated['seating_capacity'],
            'mileage' => $validated['mileage'],
            'engine_capacity' => $validated['engine_capacity'] ?? null,
            'daily_rate' => $validated['daily_rate'],
            'selling_price' => $validated['selling_price'] ?? null,
            'listing_type' => $validated['listing_type'],
            'status' => $validated['status'],
            'condition' => $validated['condition'],
            'description' => $validated['description'] ?? null,
            'features' => $features,
            'main_image' => $validated['main_image'] ?: 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1200&q=80',
            'is_featured' => $request->boolean('is_featured'),
        ]);

        // Add default primary image
        VehicleImage::create([
            'vehicle_id' => $vehicle->id,
            'image_url' => $vehicle->main_image,
            'caption' => "{$vehicle->brand} {$vehicle->model} primary image",
            'is_primary' => true,
        ]);

        ActivityLog::log('VEHICLE_CREATED', "New vehicle {$vehicle->full_name} added to inventory.", $vehicle, Auth::user());

        return redirect()->route('admin.vehicles.index')->with('success', "Vehicle {$vehicle->full_name} created successfully.");
    }

    public function edit(Vehicle $vehicle): View
    {
        $categories = VehicleCategory::where('is_active', true)->get();

        return view('admin.vehicles.edit', compact('vehicle', 'categories'));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:vehicle_categories,id'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1990', 'max:2030'],
            'vin' => ['required', 'string', 'max:50', 'unique:vehicles,vin,'.$vehicle->id],
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate,'.$vehicle->id],
            'color' => ['nullable', 'string', 'max:50'],
            'transmission' => ['required', 'string', 'max:50'],
            'fuel_type' => ['required', 'string', 'max:50'],
            'seating_capacity' => ['required', 'integer', 'min:1', 'max:12'],
            'mileage' => ['required', 'integer', 'min:0'],
            'engine_capacity' => ['nullable', 'string', 'max:100'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'listing_type' => ['required', 'in:rental,sale,both'],
            'status' => ['required', 'in:available,rented,reserved,sold,maintenance'],
            'condition' => ['required', 'in:new,used,certified_preowned'],
            'description' => ['nullable', 'string'],
            'features_list' => ['nullable', 'string'],
            'main_image' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $features = [];
        if (! empty($validated['features_list'])) {
            $features = array_filter(array_map('trim', explode("\n", $validated['features_list'])));
        }

        $vehicle->update([
            'category_id' => $validated['category_id'],
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'vin' => $validated['vin'],
            'license_plate' => $validated['license_plate'],
            'color' => $validated['color'] ?? null,
            'transmission' => $validated['transmission'],
            'fuel_type' => $validated['fuel_type'],
            'seating_capacity' => $validated['seating_capacity'],
            'mileage' => $validated['mileage'],
            'engine_capacity' => $validated['engine_capacity'] ?? null,
            'daily_rate' => $validated['daily_rate'],
            'selling_price' => $validated['selling_price'] ?? null,
            'listing_type' => $validated['listing_type'],
            'status' => $validated['status'],
            'condition' => $validated['condition'],
            'description' => $validated['description'] ?? null,
            'features' => $features,
            'main_image' => $validated['main_image'] ?: $vehicle->main_image,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        ActivityLog::log('VEHICLE_UPDATED', "Vehicle {$vehicle->full_name} details modified.", $vehicle, Auth::user());

        return redirect()->route('admin.vehicles.index')->with('success', "Vehicle {$vehicle->full_name} updated successfully.");
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $hasActiveBookings = $vehicle->rentalBookings()->whereIn('status', ['confirmed', 'active'])->exists();
        if ($hasActiveBookings) {
            return back()->withErrors(['error' => 'Cannot delete a vehicle that has active or confirmed rental bookings.']);
        }

        $name = $vehicle->full_name;
        $vehicle->delete();

        ActivityLog::log('VEHICLE_DELETED', "Vehicle {$name} removed from fleet.", null, Auth::user());

        return redirect()->route('admin.vehicles.index')->with('success', "Vehicle {$name} has been deleted.");
    }

    public function toggleStatus(Vehicle $vehicle, Request $request): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:available,rented,reserved,sold,maintenance'],
        ]);

        $vehicle->update(['status' => $request->input('status')]);

        ActivityLog::log('VEHICLE_STATUS_CHANGED', "Vehicle {$vehicle->full_name} status switched to {$vehicle->status}.", $vehicle, Auth::user());

        return back()->with('success', "Vehicle status changed to {$vehicle->status}.");
    }
}
