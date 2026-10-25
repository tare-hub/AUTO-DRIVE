<?php

namespace Tests\Unit;

use App\Mail\WelcomeCustomerMail;
use App\Models\User;
use Tests\TestCase;

class WelcomeCustomerMailTest extends TestCase
{
    public function test_welcome_customer_mail_has_correct_subject_and_content(): void
    {
        $user = new User([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+1 (555) 444-5555',
            'driver_license_number' => 'DL-1234567',
            'role' => User::ROLE_CUSTOMER,
        ]);

        $mailable = new WelcomeCustomerMail($user);

        $mailable->assertHasSubject('Welcome to AutoDrive, Jane Doe! Your Account is Ready');
        $mailable->assertSeeInHtml('Jane Doe');
        $mailable->assertSeeInHtml('jane@example.com');
        $mailable->assertSeeInHtml('DL-1234567');
        $mailable->assertSeeInText('Jane Doe');
        $mailable->assertSeeInText('jane@example.com');
    }
}
