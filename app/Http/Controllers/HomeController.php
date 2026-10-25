<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredRentals = Vehicle::with('category')
            ->whereIn('listing_type', ['rental', 'both'])
            ->where('status', 'available')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $featuredSales = Vehicle::with('category')
            ->whereIn('listing_type', ['sale', 'both'])
            ->whereIn('status', ['available', 'reserved'])
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $categories = VehicleCategory::withCount('vehicles')
            ->where('is_active', true)
            ->get();

        $featuredVehicles = Vehicle::with('category')
            ->where('is_featured', true)
            ->where('status', 'available')
            ->orderByRaw("CASE 
                WHEN brand = 'Mercedes-Benz' AND model = 'AMG GT' THEN 1
                WHEN brand = 'BMW' AND model = '5 Series' THEN 2
                WHEN brand = 'Porsche' AND model = '911 Carrera' THEN 3
                WHEN brand = 'Land Rover' AND model = 'Range Rover Sport' THEN 4
                WHEN brand = 'Toyota' AND model = 'Land Cruiser' THEN 5
                ELSE 6 END")
            ->take(8)
            ->get();

        $brands = Vehicle::select('brand')->distinct()->orderBy('brand')->pluck('brand');
        $models = Vehicle::select('model')->distinct()->orderBy('model')->pluck('model');

        return view('home', compact('featuredRentals', 'featuredSales', 'featuredVehicles', 'categories', 'brands', 'models'));
    }

    public function about(): View
    {
        return view('about');
    }

    public function contact(): View
    {
        return view('contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        return back()->with('success', 'Thank you for reaching out. Our automotive concierge team will respond within 2 hours.');
    }
}
