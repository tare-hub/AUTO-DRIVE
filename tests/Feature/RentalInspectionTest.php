<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Services\InspectionService;
use App\Services\RentalService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalInspectionTest extends TestCase
{
    use RefreshDatabase;

    protected RentalService $rentalService;

    protected InspectionService $inspectionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rentalService = app(RentalService::class);
        $this->inspectionService = app(InspectionService::class);
    }

    public function test_rental_manager_can_approve_booking(): void
    {
        $manager = User::factory()->rentalManager()->create();
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create(['status' => 'available']);

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
        ]);

        $response = $this->actingAs($manager)->post(route('rental.bookings.approve', $booking));
        $response->assertRedirect();

        $this->assertEquals('confirmed', $booking->fresh()->status);
        $this->assertEquals($manager->id, $booking->fresh()->approved_by);
    }

    public function test_rental_manager_can_reject_booking(): void
    {
        $manager = User::factory()->rentalManager()->create();
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create();

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
        ]);

        $response = $this->actingAs($manager)->post(route('rental.bookings.reject', $booking), [
            'reason' => 'Driver license verification failed.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('cancelled', $booking->fresh()->status);
        $this->assertStringContainsString('Driver license verification failed', $booking->fresh()->cancellation_reason);
    }

    public function test_pickup_inspection_activates_rental_and_marks_vehicle_rented(): void
    {
        $manager = User::factory()->rentalManager()->create();
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create([
            'status' => 'available',
            'mileage' => 10000,
        ]);

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        ]);
        $this->rentalService->approveBooking($booking, $manager);

        $response = $this->actingAs($manager)->post(route('rental.inspections.pickup.store', $booking), [
            'odometer_reading' => 10050,
            'fuel_level' => 'Full',
            'exterior_condition' => 'Pristine',
            'interior_condition' => 'Clean',
            'damages_notes' => 'Minor stone chip on hood.',
        ]);

        $response->assertRedirect(route('rental.dashboard'));

        $this->assertEquals('active', $booking->fresh()->status);
        $this->assertEquals('rented', $vehicle->fresh()->status);
        $this->assertEquals(10050, $vehicle->fresh()->mileage);

        $this->assertDatabaseHas('rental_inspections', [
            'rental_booking_id' => $booking->id,
            'type' => 'pickup',
            'odometer_reading' => 10050,
            'fuel_level' => 'Full',
        ]);
    }

    public function test_return_inspection_completes_booking_and_restores_vehicle_to_available(): void
    {
        $manager = User::factory()->rentalManager()->create();
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create([
            'status' => 'available',
            'mileage' => 10000,
        ]);

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        ]);
        $this->rentalService->approveBooking($booking, $manager);
        $this->inspectionService->recordPickupInspection($booking, $manager, [
            'odometer_reading' => 10000,
        ]);

        $response = $this->actingAs($manager)->post(route('rental.inspections.return.store', $booking), [
            'odometer_reading' => 10450,
            'fuel_level' => 'Full',
            'exterior_condition' => 'Excellent',
            'interior_condition' => 'Clean',
            'damage_charge' => 0,
        ]);

        $response->assertRedirect(route('rental.dashboard'));

        $this->assertEquals('completed', $booking->fresh()->status);
        $this->assertEquals('available', $vehicle->fresh()->status);
        $this->assertEquals(10450, $vehicle->fresh()->mileage);
    }

    public function test_return_inspection_with_damage_routes_vehicle_to_maintenance(): void
    {
        $manager = User::factory()->rentalManager()->create();
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create([
            'status' => 'available',
            'mileage' => 5000,
        ]);

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
        ]);
        $this->rentalService->approveBooking($booking, $manager);
        $this->inspectionService->recordPickupInspection($booking, $manager, [
            'odometer_reading' => 5000,
        ]);

        $initialTotal = $booking->total_amount;

        $response = $this->actingAs($manager)->post(route('rental.inspections.return.store', $booking), [
            'odometer_reading' => 5300,
            'fuel_level' => 'Half',
            'exterior_condition' => 'Damaged',
            'interior_condition' => 'Clean',
            'damages_notes' => 'Cracked front bumper and cracked headlight.',
            'damage_charge' => 1500.00,
            'requires_maintenance' => '1',
        ]);

        $response->assertRedirect(route('rental.dashboard'));

        $this->assertEquals('completed', $booking->fresh()->status);
        $this->assertEquals('maintenance', $vehicle->fresh()->status);
        $this->assertEquals($initialTotal + 1500.00, $booking->fresh()->total_amount);
        $this->assertEquals(1500.00, $booking->fresh()->damage_fee);
    }
}
