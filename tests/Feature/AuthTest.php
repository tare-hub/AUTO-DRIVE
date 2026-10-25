<?php

namespace Tests\Feature;

use App\Mail\WelcomeCustomerMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_successfully(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), [
            'name' => 'John Carter',
            'email' => 'john@example.com',
            'phone' => '+1 (555) 123-4567',
            'driver_license_number' => 'DL-987654',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => User::ROLE_CUSTOMER,
            'driver_license_number' => 'DL-987654',
        ]);

        Mail::assertSent(WelcomeCustomerMail::class, function (WelcomeCustomerMail $mail) {
            return $mail->hasTo('john@example.com')
                && $mail->user->name === 'John Carter'
                && $mail->user->email === 'john@example.com';
        });
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'user@autodrive.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'user@autodrive.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'user@autodrive.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'user@autodrive.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_demo_login_switches_to_target_role(): void
    {
        User::factory()->admin()->create(['email' => 'admin@autodrive.com']);
        User::factory()->rentalManager()->create(['email' => 'rental@autodrive.com']);
        User::factory()->salesManager()->create(['email' => 'sales@autodrive.com']);
        User::factory()->support()->create(['email' => 'support@autodrive.com']);
        User::factory()->customer()->create(['email' => 'customer@autodrive.com']);

        // Admin
        $response = $this->get(route('demo.login', 'admin'));
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertEquals(User::ROLE_ADMIN, auth()->user()->role);

        // Rental Manager
        $response = $this->get(route('demo.login', 'rental'));
        $response->assertRedirect(route('rental.dashboard'));
        $this->assertEquals(User::ROLE_RENTAL_MANAGER, auth()->user()->role);

        // Sales Manager
        $response = $this->get(route('demo.login', 'sales'));
        $response->assertRedirect(route('sales.dashboard'));
        $this->assertEquals(User::ROLE_SALES_MANAGER, auth()->user()->role);

        // Support
        $response = $this->get(route('demo.login', 'support'));
        $response->assertRedirect(route('support.dashboard'));
        $this->assertEquals(User::ROLE_SUPPORT, auth()->user()->role);

        // Customer
        $response = $this->get(route('demo.login', 'customer'));
        $response->assertRedirect(route('customer.dashboard'));
        $this->assertEquals(User::ROLE_CUSTOMER, auth()->user()->role);
    }
}
