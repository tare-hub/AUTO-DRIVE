<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\RentalBooking;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class RentalService
{
    /**
     * Calculate rental quote and itemized pricing breakdown.
     *
     * @return array{total_days: int, daily_rate: float, subtotal: float, insurance_fee: float, tax_amount: float, total_amount: float}
     */
    public function calculateQuote(Vehicle $vehicle, string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if ($end->lessThan($start)) {
            throw new InvalidArgumentException('Return date must be on or after the pickup date.');
        }

        // Billable days: same day = 1 day, otherwise diffInDays
        $totalDays = max(1, (int) $start->diffInDays($end));
        $dailyRate = (float) $vehicle->daily_rate;
        $subtotal = round($dailyRate * $totalDays, 2);
        $insuranceFee = round(50.00 * $totalDays, 2); // $50/day premium coverage
        $taxAmount = round(($subtotal + $insuranceFee) * 0.07, 2); // 7% tax rate
        $totalAmount = round($subtotal + $insuranceFee + $taxAmount, 2);

        return [
            'total_days' => $totalDays,
            'daily_rate' => $dailyRate,
            'subtotal' => $subtotal,
            'insurance_fee' => $insuranceFee,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ];
    }

    /**
     * Create a rental booking with concurrency lock and overlap validation.
     */
    public function createBooking(User $customer, Vehicle $vehicle, array $data): RentalBooking
    {
        return DB::transaction(function () use ($customer, $vehicle, $data) {
            // Lock vehicle row for update
            $lockedVehicle = Vehicle::where('id', $vehicle->id)->lockForUpdate()->firstOrFail();

            if (! in_array($lockedVehicle->listing_type, ['rental', 'both'])) {
                throw new InvalidArgumentException('This vehicle is not listed for rental.');
            }

            if ($lockedVehicle->status === 'maintenance') {
                throw new InvalidArgumentException('This vehicle is currently undergoing maintenance and is not available for rental.');
            }

            $startDate = Carbon::parse($data['start_date'])->format('Y-m-d');
            $endDate = Carbon::parse($data['end_date'])->format('Y-m-d');

            // Check overlapping bookings
            if (! $lockedVehicle->isAvailableForRentalOnDates($startDate, $endDate)) {
                throw new InvalidArgumentException('This vehicle is already reserved or booked for the chosen date range. Please select alternative dates.');
            }

            $quote = $this->calculateQuote($lockedVehicle, $startDate, $endDate);

            $booking = RentalBooking::create([
                'user_id' => $customer->id,
                'vehicle_id' => $lockedVehicle->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_days' => $quote['total_days'],
                'daily_rate' => $quote['daily_rate'],
                'subtotal' => $quote['subtotal'],
                'insurance_fee' => $quote['insurance_fee'],
                'tax_amount' => $quote['tax_amount'],
                'total_amount' => $quote['total_amount'],
                'paid_amount' => 0,
                'status' => 'pending',
                'pickup_location' => $data['pickup_location'] ?? 'AutoDrive Main Flagship Terminal',
                'return_location' => $data['return_location'] ?? 'AutoDrive Main Flagship Terminal',
                'notes' => $data['notes'] ?? null,
            ]);

            ActivityLog::log('BOOKING_CREATED', "Rental booking {$booking->booking_number} requested for {$lockedVehicle->full_name}.", $booking, $customer);

            return $booking;
        });
    }

    /**
     * Approve a rental booking request.
     */
    public function approveBooking(RentalBooking $booking, User $staff): RentalBooking
    {
        $booking->update([
            'status' => 'confirmed',
            'approved_by' => $staff->id,
            'approved_at' => Carbon::now(),
        ]);

        ActivityLog::log('BOOKING_APPROVED', "Booking {$booking->booking_number} approved by {$staff->name}.", $booking, $staff);

        return $booking;
    }

    /**
     * Reject a rental booking request.
     */
    public function rejectBooking(RentalBooking $booking, User $staff, string $reason): RentalBooking
    {
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Rejected by manager: '.$reason,
            'cancelled_at' => Carbon::now(),
        ]);

        ActivityLog::log('BOOKING_REJECTED', "Booking {$booking->booking_number} rejected by {$staff->name}: {$reason}", $booking, $staff);

        return $booking;
    }

    /**
     * Cancel an eligible booking by the customer.
     */
    public function cancelBooking(RentalBooking $booking, User $user, string $reason): RentalBooking
    {
        if (! $booking->isCancellableByCustomer() && ! $user->isAdmin() && ! $user->isRentalManager()) {
            throw new InvalidArgumentException('This booking is not eligible for cancellation at its current stage.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => Carbon::now(),
        ]);

        ActivityLog::log('BOOKING_CANCELLED', "Booking {$booking->booking_number} cancelled. Reason: {$reason}", $booking, $user);

        return $booking;
    }
}
