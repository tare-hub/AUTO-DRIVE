<?php

namespace Tests\Feature;

use App\Models\PurchaseRequest;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\SalesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class SalesTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected SalesService $salesService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->salesService = app(SalesService::class);
    }

    public function test_customer_can_submit_purchase_request(): void
    {
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->saleOnly()->create([
            'selling_price' => 110000.00,
            'status' => 'available',
        ]);

        $response = $this->actingAs($customer)->post(route('customer.purchases.store'), [
            'vehicle_id' => $vehicle->id,
            'offering_price' => 105000.00,
            'customer_notes' => 'Cash purchase, ready to wire immediately.',
        ]);

        $response->assertRedirect(route('customer.purchases'));

        $this->assertDatabaseHas('purchase_requests', [
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'offering_price' => 105000.00,
            'status' => 'pending',
            'customer_notes' => 'Cash purchase, ready to wire immediately.',
        ]);
    }

    public function test_cannot_submit_purchase_request_for_sold_vehicle(): void
    {
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->saleOnly()->sold()->create([
            'selling_price' => 95000.00,
        ]);

        $response = $this->actingAs($customer)->post(route('customer.purchases.store'), [
            'vehicle_id' => $vehicle->id,
            'offering_price' => 95000.00,
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('purchase_requests', 0);
    }

    public function test_sales_manager_review_reserves_vehicle(): void
    {
        $salesManager = User::factory()->salesManager()->create();
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->saleOnly()->create([
            'selling_price' => 125000.00,
            'status' => 'available',
        ]);

        $request = $this->salesService->createPurchaseRequest($customer, $vehicle, [
            'offering_price' => 120000.00,
        ]);

        $response = $this->actingAs($salesManager)->post(route('sales.requests.review', $request), [
            'status' => 'approved',
            'staff_notes' => 'Offer accepted. Vehicle reserved for title execution.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('approved', $request->fresh()->status);
        $this->assertEquals('reserved', $vehicle->fresh()->status);
        $this->assertEquals($salesManager->id, $request->fresh()->reviewed_by);
    }

    public function test_finalize_sale_creates_transaction_and_permanently_marks_vehicle_sold(): void
    {
        $salesManager = User::factory()->salesManager()->create();
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->saleOnly()->create([
            'selling_price' => 80000.00,
            'status' => 'available',
        ]);

        $request = $this->salesService->createPurchaseRequest($customer, $vehicle, [
            'offering_price' => 80000.00,
        ]);
        $this->salesService->reviewPurchaseRequest($request, $salesManager, 'approved');

        $response = $this->actingAs($salesManager)->post(route('sales.requests.finalize.store', $request), [
            'sale_price' => 80000.00,
            'paid_amount' => 87100.00, // 80k + 7% tax (5600) + 1500 doc fees
            'payment_method' => 'bank_transfer',
            'notes' => 'Wire funds confirmed. Title and keys released.',
        ]);

        $response->assertRedirect(route('sales.dashboard'));

        $this->assertEquals('sold', $vehicle->fresh()->status);
        $this->assertEquals('completed', $request->fresh()->status);

        $this->assertDatabaseHas('sales_transactions', [
            'purchase_request_id' => $request->id,
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'sale_price' => 80000.00,
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('payments', [
            'payable_type' => SalesTransaction::class,
            'user_id' => $customer->id,
            'payment_status' => 'paid',
            'payment_method' => 'bank_transfer',
        ]);
    }

    public function test_prevention_of_duplicate_vehicle_sales(): void
    {
        $salesManager = User::factory()->salesManager()->create();
        $customer = User::factory()->customer()->create();
        $vehicle = Vehicle::factory()->saleOnly()->create([
            'status' => 'available',
        ]);

        $request1 = $this->salesService->createPurchaseRequest($customer, $vehicle, [
            'offering_price' => 90000.00,
        ]);

        // Complete sale 1
        $this->salesService->finalizeSale($request1, $salesManager, [
            'sale_price' => 90000.00,
        ]);

        $this->assertEquals('sold', $vehicle->fresh()->status);

        // Attempt second sale on the same vehicle
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('This vehicle has already been marked as sold.');

        $request2 = PurchaseRequest::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'offering_price' => 90000.00,
            'status' => 'approved',
        ]);

        $this->salesService->finalizeSale($request2, $salesManager, [
            'sale_price' => 90000.00,
        ]);
    }
}
