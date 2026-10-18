<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\PurchaseRequest;
use App\Models\RentalBooking;
use App\Models\SalesTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $activeBookingsCount = RentalBooking::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'active'])
            ->count();

        $totalSpent = RentalBooking::where('user_id', $user->id)->sum('paid_amount')
            + SalesTransaction::where('customer_id', $user->id)->sum('paid_amount');

        $pendingPurchasesCount = PurchaseRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->count();

        $recentBookings = RentalBooking::with(['vehicle', 'vehicle.category'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $recentPurchases = PurchaseRequest::with(['vehicle'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'user',
            'activeBookingsCount',
            'totalSpent',
            'pendingPurchasesCount',
            'recentBookings',
            'recentPurchases',
            'recentPayments'
        ));
    }

    public function profile(): View
    {
        $user = Auth::user();

        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'driver_license_number' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update($validated);

        ActivityLog::log('PROFILE_UPDATED', 'Customer updated contact information.', null, $user);

        return back()->with('success', 'Your profile details have been updated.');
    }

    public function bookings(): View
    {
        $user = Auth::user();
        $bookings = RentalBooking::with(['vehicle', 'inspections'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('customer.bookings', compact('bookings'));
    }

    public function purchases(): View
    {
        $user = Auth::user();
        $purchaseRequests = PurchaseRequest::with(['vehicle', 'salesTransaction'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('customer.purchases', compact('purchaseRequests'));
    }

    public function payments(): View
    {
        $user = Auth::user();
        $payments = Payment::with(['payable'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('customer.payments', compact('payments'));
    }
}
