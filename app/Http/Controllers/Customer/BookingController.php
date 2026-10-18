<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\RentalBooking;
use App\Models\Vehicle;
use App\Services\RentalService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(
        protected RentalService $rentalService
    ) {}

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pickup_location' => ['required', 'string', 'max:255'],
            'return_location' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        try {
            $booking = $this->rentalService->createBooking(Auth::user(), $vehicle, $validated);

            return redirect()->route('customer.bookings')
                ->with('success', "Rental reservation request #{$booking->booking_number} submitted! Our rental concierge will review and confirm shortly.");
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['booking_error' => $e->getMessage()]);
        }
    }

    public function cancel(RentalBooking $booking, Request $request): RedirectResponse
    {
        // Authorization check: customer can only cancel their own booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        try {
            $this->rentalService->cancelBooking($booking, Auth::user(), $validated['cancellation_reason']);

            return back()->with('success', "Booking #{$booking->booking_number} has been cancelled.");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
