<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Services\PaymentService;
use App\Services\RentalService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected RentalService $rentalService;

    protected PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rentalService = app(RentalService::class);
        $this->paymentService = app(PaymentService::class);
    }

    public function test_customer_can_pay_rental_booking_partially_and_fully(): void
    {
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create(['daily_rate' => 200.00]);

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(4)->format('Y-m-d'), // 2 days
        ]);

        $totalAmount = $booking->total_amount; // e.g. ~ 2 * 200 + 100 + tax = 535.00

        // Pay $200 partially
        $response = $this->actingAs($customer)->post(route('payments.booking', $booking), [
            'amount' => 200.00,
            'payment_method' => 'simulated_card',
        ]);

        $response->assertRedirect();
        $this->assertEquals(200.00, $booking->fresh()->paid_amount);
        $this->assertEquals('partially_paid', $booking->fresh()->payment_status);

        // Pay remainder
        $remainder = $totalAmount - 200.00;
        $this->actingAs($customer)->post(route('payments.booking', $booking), [
            'amount' => $remainder,
            'payment_method' => 'simulated_card',
        ]);

        $this->assertEquals($totalAmount, $booking->fresh()->paid_amount);
        $this->assertEquals('paid', $booking->fresh()->payment_status);
        $this->assertEquals(0.00, $booking->fresh()->balance_due);
    }

    public function test_customer_can_view_their_own_receipt(): void
    {
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create();

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        ]);

        $payment = $this->paymentService->recordPayment(
            $booking,
            $customer,
            250.00,
            'simulated_card',
            'Downpayment deposit'
        );

        $response = $this->actingAs($customer)->get(route('payments.receipt', $payment));

        $response->assertOk();
        $response->assertSee($payment->transaction_reference);
        $response->assertSee('250.00');
        $response->assertSee('Verified Transaction');
        $response->assertSee('Official Receipt');
    }

    public function test_customer_cannot_view_another_customer_receipt(): void
    {
        $customerA = User::factory()->customer()->create();
        $customerB = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->create();

        $booking = $this->rentalService->createBooking($customerA, $vehicle, [
            'start_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        ]);

        $payment = $this->paymentService->recordPayment(
            $booking,
            $customerA,
            300.00,
            'simulated_card'
        );

        // Customer B attempts to open Customer A's receipt
        $response = $this->actingAs($customerB)->get(route('payments.receipt', $payment));
        $response->assertForbidden();
    }

    public function test_staff_can_view_any_customer_receipt(): void
    {
        $customer = User::factory()->customer()->create();
        $supportStaff = User::factory()->support()->create();
        $vehicle = Vehicle::factory()->create();

        $booking = $this->rentalService->createBooking($customer, $vehicle, [
            'start_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        ]);

        $payment = $this->paymentService->recordPayment(
            $booking,
            $customer,
            400.00,
            'bank_transfer'
        );

        $response = $this->actingAs($supportStaff)->get(route('payments.receipt', $payment));
        $response->assertOk();
        $response->assertSee($payment->transaction_reference);
    }
}
