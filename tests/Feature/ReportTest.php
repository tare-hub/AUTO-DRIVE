<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected ReportService $reportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportService = app(ReportService::class);
    }

    public function test_executive_metrics_calculation(): void
    {
        $cat = VehicleCategory::factory()->create();

        // 2 available rental cars
        Vehicle::factory()->count(2)->rentalOnly()->create([
            'category_id' => $cat->id,
            'status' => 'available',
        ]);

        // 1 rented car
        Vehicle::factory()->rented()->create([
            'category_id' => $cat->id,
        ]);

        // 1 maintenance car
        Vehicle::factory()->maintenance()->create([
            'category_id' => $cat->id,
        ]);

        // 1 sold car
        Vehicle::factory()->sold()->create([
            'category_id' => $cat->id,
        ]);

        $metrics = $this->reportService->getExecutiveMetrics();

        $this->assertEquals(5, $metrics['total_vehicles']);
        $this->assertEquals(2, $metrics['available_rental']);
        $this->assertEquals(1, $metrics['currently_rented']);
        $this->assertEquals(1, $metrics['under_maintenance']);
        $this->assertEquals(1, $metrics['sold_vehicles']);
    }

    public function test_admin_can_view_reports_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.reports.index'));

        $response->assertOk();
        $response->assertSee('Instant Data Export');
        $response->assertSee('Rental Revenue Streams');
    }

    public function test_admin_can_export_rentals_sales_and_inventory_csv(): void
    {
        $admin = User::factory()->admin()->create();
        Vehicle::factory()->create();

        // Export Rentals CSV
        $respRentals = $this->actingAs($admin)->get(route('admin.reports.export', ['type' => 'rentals']));
        $respRentals->assertOk();
        $respRentals->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        // Export Sales CSV
        $respSales = $this->actingAs($admin)->get(route('admin.reports.export', ['type' => 'sales']));
        $respSales->assertOk();
        $respSales->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        // Export Inventory CSV
        $respInventory = $this->actingAs($admin)->get(route('admin.reports.export', ['type' => 'inventory']));
        $respInventory->assertOk();
        $respInventory->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_customer_cannot_access_reports_or_exports(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get(route('admin.reports.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('admin.reports.export', ['type' => 'rentals']))->assertForbidden();
    }
}
