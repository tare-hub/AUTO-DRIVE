<?php

namespace Tests\Feature;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_support_ticket(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($customer)->post(route('customer.tickets.store'), [
            'subject' => 'Inquiry regarding Airport VIP delivery service',
            'category' => 'rental',
            'priority' => 'high',
            'message' => 'Can the vehicle be dropped off directly at the private jet terminal?',
        ]);

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $customer->id,
            'subject' => 'Inquiry regarding Airport VIP delivery service',
            'category' => 'rental',
            'priority' => 'high',
            'status' => 'open',
        ]);

        $ticket = SupportTicket::where('subject', 'Inquiry regarding Airport VIP delivery service')->first();
        $response->assertRedirect(route('customer.tickets.show', $ticket));

        $this->assertDatabaseHas('support_messages', [
            'support_ticket_id' => $ticket->id,
            'user_id' => $customer->id,
            'is_staff' => false,
        ]);
    }

    public function test_support_staff_can_view_and_reply_to_ticket(): void
    {
        $customer = User::factory()->customer()->create();
        $supportStaff = User::factory()->support()->create();

        $ticket = SupportTicket::create([
            'user_id' => $customer->id,
            'subject' => 'Payment question',
            'category' => 'payment',
            'priority' => 'medium',
            'status' => 'open',
        ]);

        $response = $this->actingAs($supportStaff)->post(route('support.tickets.reply', $ticket), [
            'message' => 'Hello! Yes, wire transfers clear within 2 hours during normal business hours.',
            'status' => 'in_progress',
        ]);

        $response->assertRedirect();

        $this->assertEquals('in_progress', $ticket->fresh()->status);
        $this->assertEquals($supportStaff->id, $ticket->fresh()->assigned_to);

        $this->assertDatabaseHas('support_messages', [
            'support_ticket_id' => $ticket->id,
            'user_id' => $supportStaff->id,
            'is_staff' => true,
        ]);
    }

    public function test_support_staff_can_update_ticket_status(): void
    {
        $customer = User::factory()->customer()->create();
        $supportStaff = User::factory()->support()->create();

        $ticket = SupportTicket::create([
            'user_id' => $customer->id,
            'subject' => 'Booking date extension',
            'category' => 'rental',
            'priority' => 'low',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($supportStaff)->patch(route('support.tickets.status', $ticket), [
            'status' => 'resolved',
        ]);

        $response->assertRedirect();
        $this->assertEquals('resolved', $ticket->fresh()->status);
    }
}
