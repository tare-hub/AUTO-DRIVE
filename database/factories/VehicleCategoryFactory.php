<?php

namespace Database\Factories;

use App\Models\VehicleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<VehicleCategory>
 */
class VehicleCategoryFactory extends Factory
{
    protected $model = VehicleCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Luxury Sedan', 'Performance SUV', 'Exotic Sports', 'Grand Tourer', 'Executive Electric']);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'description' => fake()->sentence(),
            'icon' => 'fa-car',
            'is_active' => true,
        ];
    }
}
