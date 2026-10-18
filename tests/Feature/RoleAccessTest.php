<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_protected_routes(): void
    {
        $this->get(route('customer.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('rental.dashboard'))->assertRedirect(route('login'));
        $this->get(route('sales.dashboard'))->assertRedirect(route('login'));
        $this->get(route('support.dashboard'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_staff_dashboards(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($customer)->get(route('rental.dashboard'))->assertForbidden();
        $this->actingAs($customer)->get(route('sales.dashboard'))->assertForbidden();
        $this->actingAs($customer)->get(route('support.dashboard'))->assertForbidden();
        $this->actingAs($customer)->get(route('customer.dashboard'))->assertOk();
    }

    public function test_rental_manager_access_restrictions(): void
    {
        $rentalManager = User::factory()->rentalManager()->create();

        $this->actingAs($rentalManager)->get(route('rental.dashboard'))->assertOk();
        $this->actingAs($rentalManager)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($rentalManager)->get(route('sales.dashboard'))->assertForbidden();
    }

    public function test_sales_manager_access_restrictions(): void
    {
        $salesManager = User::factory()->salesManager()->create();

        $this->actingAs($salesManager)->get(route('sales.dashboard'))->assertOk();
        $this->actingAs($salesManager)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($salesManager)->get(route('rental.dashboard'))->assertForbidden();
    }

    public function test_admin_has_unrestricted_dashboard_access(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('rental.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('sales.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('support.dashboard'))->assertOk();
    }
}
