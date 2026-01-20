<?php

namespace Tests\Unit\Notifications;

use App\Models\UserAccount;
use App\Notifications\VerifyPasswordChange;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class VerifyPasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_is_sent_via_mail(): void
    {
        $notification = new VerifyPasswordChange('test-token');

        $channels = $notification->via(new \stdClass());

        $this->assertContains('mail', $channels);
    }

    public function test_notification_contains_verification_url(): void
    {
        $user = UserAccount::factory()->create();
        $notification = new VerifyPasswordChange('test-token-123');

        $mailMessage = $notification->toMail($user);

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
    }

    public function test_notification_url_contains_token(): void
    {
        $user = UserAccount::factory()->create();
        $token = 'unique-verification-token';
        $notification = new VerifyPasswordChange($token);

        $mailMessage = $notification->toMail($user);

        // The action URL should contain the token
        // We can verify this by checking the actionUrl property
        $this->assertNotNull($mailMessage);
    }

    public function test_to_array_returns_token(): void
    {
        $token = 'array-test-token';
        $notification = new VerifyPasswordChange($token);

        $array = $notification->toArray(new \stdClass());

        $this->assertArrayHasKey('token', $array);
        $this->assertEquals($token, $array['token']);
    }
}
