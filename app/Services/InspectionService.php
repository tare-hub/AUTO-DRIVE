<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\RentalBooking;
use App\Models\RentalInspection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InspectionService
{
    /**
     * Record pickup inspection and mark rental as active.
     */
    public function recordPickupInspection(RentalBooking $booking, User $inspector, array $data): RentalInspection
    {
        return DB::transaction(function () use ($booking, $inspector, $data) {
            $inspection = RentalInspection::create([
                'rental_booking_id' => $booking->id,
                'vehicle_id' => $booking->vehicle_id,
                'inspector_id' => $inspector->id,
                'type' => 'pickup',
                'odometer_reading' => (int) $data['odometer_reading'],
                'fuel_level' => $data['fuel_level'] ?? 'Full',
                'exterior_condition' => $data['exterior_condition'] ?? 'Pristine',
                'interior_condition' => $data['interior_condition'] ?? 'Clean',
                'damages_notes' => $data['damages_notes'] ?? null,
                'damage_charge' => 0,
                'late_days' => 0,
                'late_fee' => 0,
                'inspection_date' => Carbon::now(),
            ]);

            // Update booking to active
            $booking->update(['status' => 'active']);

            // Update vehicle status to rented
            $booking->vehicle->update([
                'status' => 'rented',
                'mileage' => (int) $data['odometer_reading'],
            ]);

            ActivityLog::log('INSPECTION_PICKUP', "Vehicle {$booking->vehicle->full_name} handed over for booking {$booking->booking_number}.", $inspection, $inspector);

            return $inspection;
        });
    }

    /**
     * Record return inspection, calculate damage/late charges, and return vehicle to available inventory.
     */
    public function recordReturnInspection(RentalBooking $booking, User $inspector, array $data): RentalInspection
    {
        return DB::transaction(function () use ($booking, $inspector, $data) {
            $expectedReturn = Carbon::parse($booking->end_date)->endOfDay();
            $now = Carbon::now();

            $lateDays = 0;
            $lateFee = 0.00;
            if ($now->greaterThan($expectedReturn)) {
                $lateDays = max(1, (int) $expectedReturn->diffInDays($now));
                // Late fee rule: $150 penalty + daily rate per overdue day
                $lateFee = round(($booking->daily_rate + 150.00) * $lateDays, 2);
            }

            $damageCharge = isset($data['damage_charge']) ? (float) $data['damage_charge'] : 0.00;
            $requiresMaintenance = ! empty($data['requires_maintenance']) || $damageCharge > 1000;

            $inspection = RentalInspection::create([
                'rental_booking_id' => $booking->id,
                'vehicle_id' => $booking->vehicle_id,
                'inspector_id' => $inspector->id,
                'type' => 'return',
                'odometer_reading' => (int) $data['odometer_reading'],
                'fuel_level' => $data['fuel_level'] ?? 'Full',
                'exterior_condition' => $data['exterior_condition'] ?? 'Good',
                'interior_condition' => $data['interior_condition'] ?? 'Clean',
                'damages_notes' => $data['damages_notes'] ?? null,
                'damage_charge' => $damageCharge,
                'late_days' => $lateDays,
                'late_fee' => $lateFee,
                'inspection_date' => Carbon::now(),
            ]);

            // Update booking with fees and completed status
            $newTotal = $booking->total_amount + $damageCharge + $lateFee;
            $booking->update([
                'status' => 'completed',
                'damage_fee' => $booking->damage_fee + $damageCharge,
                'late_fee' => $booking->late_fee + $lateFee,
                'total_amount' => $newTotal,
            ]);

            // Return vehicle to inventory (or maintenance if needed)
            $vehicleStatus = $requiresMaintenance ? 'maintenance' : 'available';
            $booking->vehicle->update([
                'status' => $vehicleStatus,
                'mileage' => (int) $data['odometer_reading'],
            ]);

            ActivityLog::log('INSPECTION_RETURN', "Vehicle {$booking->vehicle->full_name} return inspection complete for booking {$booking->booking_number}. Marked as {$vehicleStatus}.", $inspection, $inspector);

            return $inspection;
        });
    }
}
