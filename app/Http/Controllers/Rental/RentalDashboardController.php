<?php

namespace App\Http\Controllers\Rental;

use App\Http\Controllers\Controller;
use App\Models\RentalBooking;
use App\Models\Vehicle;
use App\Services\InspectionService;
use App\Services\RentalService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RentalDashboardController extends Controller
{
    public function __construct(
        protected RentalService $rentalService,
        protected InspectionService $inspectionService
    ) {}

    public function index(Request $request): View
    {
        $status = $request->input('status', 'all');

        $query = RentalBooking::with(['user', 'vehicle', 'inspections']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'pending' => RentalBooking::where('status', 'pending')->count(),
            'confirmed' => RentalBooking::where('status', 'confirmed')->count(),
            'active' => RentalBooking::where('status', 'active')->count(),
            'completed' => RentalBooking::where('status', 'completed')->count(),
            'vehicles_available' => Vehicle::availableForRental()->count(),
            'vehicles_rented' => Vehicle::where('status', 'rented')->count(),
        ];

        return view('rental.dashboard', compact('bookings', 'stats', 'status'));
    }

    public function approve(RentalBooking $booking): RedirectResponse
    {
        try {
            $this->rentalService->approveBooking($booking, Auth::user());

            return back()->with('success', "Booking #{$booking->booking_number} approved and confirmed.");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reject(RentalBooking $booking, Request $request): RedirectResponse
    {
        $request->validate(['reason' => 'required|string|max:500']);

        try {
            $this->rentalService->rejectBooking($booking, Auth::user(), $request->input('reason'));

            return back()->with('success', "Booking #{$booking->booking_number} has been rejected.");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showPickupInspection(RentalBooking $booking): View
    {
        $booking->load(['vehicle', 'user']);

        return view('rental.inspection-pickup', compact('booking'));
    }

    public function storePickupInspection(RentalBooking $booking, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'odometer_reading' => ['required', 'integer', 'min:0'],
            'fuel_level' => ['required', 'string', 'max:50'],
            'exterior_condition' => ['required', 'string', 'max:255'],
            'interior_condition' => ['required', 'string', 'max:255'],
            'damages_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $this->inspectionService->recordPickupInspection($booking, Auth::user(), $validated);

            return redirect()->route('rental.dashboard')
                ->with('success', "Pickup inspection completed! Booking #{$booking->booking_number} is now ACTIVE, and vehicle {$booking->vehicle->full_name} is marked as RENTED.");
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showReturnInspection(RentalBooking $booking): View
    {
        $booking->load(['vehicle', 'user', 'inspections']);

        return view('rental.inspection-return', compact('booking'));
    }

    public function storeReturnInspection(RentalBooking $booking, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'odometer_reading' => ['required', 'integer', 'min:'.$booking->vehicle->mileage],
            'fuel_level' => ['required', 'string', 'max:50'],
            'exterior_condition' => ['required', 'string', 'max:255'],
            'interior_condition' => ['required', 'string', 'max:255'],
            'damages_notes' => ['nullable', 'string', 'max:1000'],
            'damage_charge' => ['nullable', 'numeric', 'min:0'],
            'requires_maintenance' => ['nullable', 'boolean'],
        ]);

        try {
            $inspection = $this->inspectionService->recordReturnInspection($booking, Auth::user(), $validated);

            $statusMsg = "Return inspection recorded successfully! Booking #{$booking->booking_number} is COMPLETED.";
            if ($inspection->damage_charge > 0 || $inspection->late_fee > 0) {
                $statusMsg .= " Assessed Damage: \${$inspection->damage_charge}, Late Fee: \${$inspection->late_fee}. Total booking adjusted.";
            }

            return redirect()->route('rental.dashboard')->with('success', $statusMsg);
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
