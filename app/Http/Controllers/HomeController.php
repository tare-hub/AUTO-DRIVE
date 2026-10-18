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

        $brands = Vehicle::select('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('home', compact('featuredRentals', 'featuredSales', 'categories', 'brands'));
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
