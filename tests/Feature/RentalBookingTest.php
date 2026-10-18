<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Services\RentalService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalBookingTest extends TestCase
{
    use RefreshDatabase;

    protected RentalService $rentalService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rentalService = app(RentalService::class);
    }

    public function test_rental_quote_calculation(): void
    {
        $vehicle = Vehicle::factory()->create([
            'daily_rate' => 300.00,
        ]);

        $startDate = '2026-11-01';
        $endDate = '2026-11-04'; // 3 days

        $quote = $this->rentalService->calculateQuote($vehicle, $startDate, $endDate);

        $this->assertEquals(3, $quote['total_days']);
        $this->assertEquals(300.00, $quote['daily_rate']);
        $this->assertEquals(900.00, $quote['subtotal']); // 3 * 300
        $this->assertEquals(150.00, $quote['insurance_fee']); // 3 * 50
        $this->assertEquals(73.50, $quote['tax_amount']); // (900 + 150) * 0.07 = 73.50
        $this->assertEquals(1123.50, $quote['total_amount']); // 900 + 150 + 73.50
    }

    public function test_customer_can_create_a_rental_booking(): void
    {
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create([
            'daily_rate' => 200.00,
            'status' => 'available',
            'listing_type' => 'both',
        ]);

        $startDate = Carbon::now()->addDays(2)->format('Y-m-d');
        $endDate = Carbon::now()->addDays(5)->format('Y-m-d');

        $response = $this->actingAs($customer)->post(route('customer.bookings.store'), [
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'pickup_location' => 'Main Showroom',
            'return_location' => 'Airport Terminal',
            'notes' => 'Please provide child seat.',
        ]);

        $response->assertRedirect(route('customer.bookings'));

        $this->assertDatabaseHas('rental_bookings', [
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'pending',
            'pickup_location' => 'Main Showroom',
            'return_location' => 'Airport Terminal',
        ]);
    }

    public function test_booking_validation_fails_when_dates_are_invalid(): void
    {
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create(['status' => 'available']);

        // Return date before pickup date
        $response = $this->actingAs($customer)->post(route('customer.bookings.store'), [
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'pickup_location' => 'Main Showroom',
            'return_location' => 'Airport Terminal',
        ]);

        $response->assertSessionHasErrors('end_date');
    }

    public function test_prevention_of_overlapping_confirmed_bookings(): void
    {
        $customer1 = User::factory()->customer()->create();
        $customer2 = User::factory()->customer()->create();

        $vehicle = Vehicle::factory()->create([
            'status' => 'available',
            'listing_type' => 'both',
        ]);

        // Customer 1 books from day 10 to day 15
        $resp1 = $this->actingAs($customer1)->post(route('customer.bookings.store'), [
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'pickup_location' => 'Main Showroom',
            'return_location' => 'Main Showroom',
        ]);
        $resp1->assertRedirect(route('customer.bookings'));

        // Customer 2 attempts to book overlapping dates: day 12 to day 17
        $response = $this->actingAs($customer2)->post(route('customer.bookings.store'), [
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::now()->addDays(12)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(17)->format('Y-m-d'),
            'pickup_location' => 'Main Showroom',
            'return_location' => 'Main Showroom',
        ]);

        $response->assertSessionHasErrors('booking_error');
        $this->assertDatabaseCount('rental_bookings', 1);
    }

    public function test_customer_can_cancel_pending_booking(): void
    {
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create();

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(6)->format('Y-m-d'),
        ]);

        $this->assertEquals('pending', $booking->status);

        $response = $this->actingAs($customer)->post(route('customer.bookings.cancel', $booking), [
            'cancellation_reason' => 'Travel plans changed',
        ]);

        $response->assertRedirect();
        $this->assertEquals('cancelled', $booking->fresh()->status);
        $this->assertEquals('Travel plans changed', $booking->fresh()->cancellation_reason);
    }

    public function test_customer_cannot_cancel_active_booking(): void
    {
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create();

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->subDay()->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        ]);

        // Manually move to active (vehicle is on the road)
        $booking->update(['status' => 'active']);

        $response = $this->actingAs($customer)->post(route('customer.bookings.cancel', $booking), [
            'cancellation_reason' => 'I want to cancel while driving',
        ]);

        $response->assertSessionHasErrors();
        $this->assertEquals('active', $booking->fresh()->status);
    }
}
