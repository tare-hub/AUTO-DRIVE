<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Rent a Car marketplace.
     */
    public function rentIndex(Request $request): View
    {
        $query = Vehicle::with(['category', 'images'])
            ->whereIn('listing_type', ['rental', 'both']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Brand
        if ($brand = $request->input('brand')) {
            $query->where('brand', $brand);
        }

        // Transmission
        if ($transmission = $request->input('transmission')) {
            $query->where('transmission', $transmission);
        }

        // Fuel Type
        if ($fuelType = $request->input('fuel_type')) {
            $query->where('fuel_type', $fuelType);
        }

        // Seating Capacity
        if ($seats = $request->input('seating_capacity')) {
            $query->where('seating_capacity', '>=', (int) $seats);
        }

        // Max Daily Rate
        if ($maxRate = $request->input('max_daily_rate')) {
            $query->where('daily_rate', '<=', (float) $maxRate);
        }

        // Availability filter
        if ($availability = $request->input('availability')) {
            if ($availability === 'available') {
                $query->where('status', 'available');
            } elseif ($availability === 'rented') {
                $query->where('status', 'rented');
            }
        }

        // Sorting
        $sort = $request->input('sort', 'featured');
        match ($sort) {
            'price_low' => $query->orderBy('daily_rate', 'asc'),
            'price_high' => $query->orderBy('daily_rate', 'desc'),
            'newest' => $query->orderBy('year', 'desc')->orderBy('created_at', 'desc'),
            'brand' => $query->orderBy('brand', 'asc')->orderBy('model', 'asc'),
            default => $query->orderByDesc('is_featured')->latest(),
        };

        $vehicles = $query->paginate(9)->withQueryString();

        $categories = VehicleCategory::where('is_active', true)->get();
        $brands = Vehicle::whereIn('listing_type', ['rental', 'both'])->distinct()->pluck('brand');
        $viewMode = $request->input('view', 'grid');

        return view('vehicles.rent', compact('vehicles', 'categories', 'brands', 'viewMode'));
    }

    /**
     * Buy a Car marketplace.
     */
    public function buyIndex(Request $request): View
    {
        $query = Vehicle::with(['category', 'images'])
            ->whereIn('listing_type', ['sale', 'both']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Category
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Brand
        if ($brand = $request->input('brand')) {
            $query->where('brand', $brand);
        }

        // Condition
        if ($condition = $request->input('condition')) {
            $query->where('condition', $condition);
        }

        // Status (Available, Reserved, Sold)
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Max Selling Price
        if ($maxPrice = $request->input('max_price')) {
            $query->where('selling_price', '<=', (float) $maxPrice);
        }

        // Min Year
        if ($minYear = $request->input('min_year')) {
            $query->where('year', '>=', (int) $minYear);
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_low' => $query->orderBy('selling_price', 'asc'),
            'price_high' => $query->orderBy('selling_price', 'desc'),
            'mileage_low' => $query->orderBy('mileage', 'asc'),
            'brand' => $query->orderBy('brand', 'asc'),
            default => $query->orderByDesc('is_featured')->latest(),
        };

        $vehicles = $query->paginate(9)->withQueryString();

        $categories = VehicleCategory::where('is_active', true)->get();
        $brands = Vehicle::whereIn('listing_type', ['sale', 'both'])->distinct()->pluck('brand');

        return view('vehicles.buy', compact('vehicles', 'categories', 'brands'));
    }

    /**
     * Vehicle Details Page.
     */
    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['category', 'images']);

        $relatedVehicles = Vehicle::with(['category'])
            ->where('category_id', $vehicle->category_id)
            ->where('id', '!=', $vehicle->id)
            ->take(3)
            ->get();

        return view('vehicles.show', compact('vehicle', 'relatedVehicles'));
    }
}
