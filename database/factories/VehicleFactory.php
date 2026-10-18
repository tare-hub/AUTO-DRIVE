<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'category_id' => VehicleCategory::factory(),
            'brand' => fake()->randomElement(['Porsche', 'BMW', 'Mercedes-Benz', 'Audi', 'Tesla']),
            'model' => fake()->randomElement(['Panamera', 'M5 Competition', 'AMG GT', 'RS7', 'Model S Plaid']),
            'year' => fake()->numberBetween(2021, 2025),
            'vin' => strtoupper(fake()->bothify('1HGCR2F8#HA######')),
            'license_plate' => strtoupper(fake()->bothify('??-####-??')),
            'color' => fake()->randomElement(['Obsidian Black', 'Chalk Grey', 'Silver Metallic', 'Midnight Blue']),
            'transmission' => fake()->randomElement(['Automatic', 'Manual', 'Dual-Clutch']),
            'fuel_type' => fake()->randomElement(['Petrol', 'Electric', 'Hybrid']),
            'seating_capacity' => fake()->randomElement([2, 4, 5, 7]),
            'mileage' => fake()->numberBetween(500, 25000),
            'engine_capacity' => '4.0L Twin-Turbo V8',
            'condition' => 'certified_preowned',
            'listing_type' => 'both',
            'daily_rate' => 250.00,
            'selling_price' => 85000.00,
            'status' => 'available',
            'is_featured' => true,
            'main_image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1200&q=80',
            'description' => fake()->paragraph(),
            'features' => ['Sport Chrono Package', 'Carbon Ceramic Brakes', 'Burmester 3D Sound', 'Panoramic Sunroof'],
        ];
    }

    public function rentalOnly(): static
    {
        return $this->state(fn () => [
            'listing_type' => 'rental',
            'selling_price' => null,
            'daily_rate' => 200.00,
        ]);
    }

    public function saleOnly(): static
    {
        return $this->state(fn () => [
            'listing_type' => 'sale',
            'daily_rate' => 0.00,
            'selling_price' => 95000.00,
        ]);
    }

    public function sold(): static
    {
        return $this->state(fn () => [
            'status' => 'sold',
        ]);
    }

    public function reserved(): static
    {
        return $this->state(fn () => [
            'status' => 'reserved',
        ]);
    }

    public function rented(): static
    {
        return $this->state(fn () => [
            'status' => 'rented',
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn () => [
            'status' => 'maintenance',
        ]);
    }
}
