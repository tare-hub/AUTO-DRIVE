<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => User::ROLE_CUSTOMER,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'driver_license_number' => 'DL-'.fake()->numerify('######'),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_ADMIN]);
    }

    public function rentalManager(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_RENTAL_MANAGER]);
    }

    public function salesManager(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_SALES_MANAGER]);
    }

    public function support(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_SUPPORT]);
    }

    public function customer(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_CUSTOMER]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
