<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_rental_page_displays_rental_eligible_vehicles_only(): void
    {
        $rentalCar = Vehicle::factory()->rentalOnly()->create([
            'brand' => 'Porsche',
            'model' => '911 Carrera',
            'daily_rate' => 350.00,
            'status' => 'available',
        ]);

        $bothCar = Vehicle::factory()->create([
            'brand' => 'BMW',
            'model' => 'M4 Coupe',
            'listing_type' => 'both',
            'status' => 'available',
        ]);

        $saleCar = Vehicle::factory()->saleOnly()->create([
            'brand' => 'Ferrari',
            'model' => 'F8 Tributo',
            'status' => 'available',
        ]);

        $response = $this->get(route('vehicles.rent'));

        $response->assertOk();
        $response->assertSee('911 Carrera');
        $response->assertSee('M4 Coupe');
        $response->assertDontSee('F8 Tributo');
    }

    public function test_sales_page_displays_sale_eligible_vehicles_only(): void
    {
        $saleCar = Vehicle::factory()->saleOnly()->create([
            'brand' => 'Mercedes-Benz',
            'model' => 'S 580',
            'status' => 'available',
        ]);

        $rentalCar = Vehicle::factory()->rentalOnly()->create([
            'brand' => 'Audi',
            'model' => 'RS6 Avant',
            'status' => 'available',
        ]);

        $response = $this->get(route('vehicles.buy'));

        $response->assertOk();
        $response->assertSee('S 580');
        $response->assertDontSee('RS6 Avant');
    }

    public function test_vehicle_filtering_by_category_and_transmission(): void
    {
        $catSUV = VehicleCategory::factory()->create(['name' => 'High-Performance SUV']);
        $catSedan = VehicleCategory::factory()->create(['name' => 'Executive Sedan']);

        $suv = Vehicle::factory()->create([
            'category_id' => $catSUV->id,
            'brand' => 'Lamborghini',
            'model' => 'Urus Performante',
            'transmission' => 'Automatic',
            'listing_type' => 'both',
            'status' => 'available',
        ]);

        $sedan = Vehicle::factory()->create([
            'category_id' => $catSedan->id,
            'brand' => 'BMW',
            'model' => 'M5 Competition Super-Sedan',
            'transmission' => 'Dual-Clutch',
            'listing_type' => 'both',
            'status' => 'available',
        ]);

        $response = $this->get(route('vehicles.rent', [
            'category_id' => $catSUV->id,
            'transmission' => 'Automatic',
        ]));

        $response->assertOk();
        $response->assertSee('Urus Performante');
        $response->assertDontSee('M5 Competition Super-Sedan');
    }

    public function test_vehicle_search_by_brand_keyword(): void
    {
        Vehicle::factory()->create([
            'brand' => 'Bentley',
            'model' => 'Continental GT',
            'status' => 'available',
        ]);

        Vehicle::factory()->create([
            'brand' => 'McLaren',
            'model' => '720S',
            'status' => 'available',
        ]);

        $response = $this->get(route('vehicles.rent', ['search' => 'Bentley']));

        $response->assertOk();
        $response->assertSee('Continental GT');
        $response->assertDontSee('720S');
    }

    public function test_vehicle_sorting_by_price_ascending(): void
    {
        $cheap = Vehicle::factory()->create([
            'brand' => 'Tesla',
            'model' => 'Model 3 Performance',
            'daily_rate' => 120.00,
            'status' => 'available',
        ]);

        $expensive = Vehicle::factory()->create([
            'brand' => 'Rolls-Royce',
            'model' => 'Ghost',
            'daily_rate' => 950.00,
            'status' => 'available',
        ]);

        $response = $this->get(route('vehicles.rent', ['sort' => 'price_asc']));

        $response->assertOk();
        $response->assertSeeInOrder(['Model 3 Performance', 'Ghost']);
    }

    public function test_vehicle_details_page_loads_with_specifications(): void
    {
        $vehicle = Vehicle::factory()->create([
            'brand' => 'Aston Martin',
            'model' => 'DBS Superleggera',
            'year' => 2024,
            'vin' => '1HGCR2F89HA123456',
            'fuel_type' => 'Petrol',
            'engine_capacity' => '5.2L Twin-Turbo V12',
            'status' => 'available',
        ]);

        $response = $this->get(route('vehicles.show', $vehicle));

        $response->assertOk();
        $response->assertSee('Aston Martin');
        $response->assertSee('DBS Superleggera');
        $response->assertSee('5.2L Twin-Turbo V12');
        $response->assertSee('1HGCR2F89HA123456');
    }
}
