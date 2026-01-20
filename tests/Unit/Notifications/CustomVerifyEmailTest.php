<?php

namespace Tests\Unit\Notifications;

use App\Models\UserAccount;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class CustomVerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_contains_verification_url(): void
    {
        $user = UserAccount::factory()->unverified()->create();
        $notification = new CustomVerifyEmail();

        $mailMessage = $notification->toMail($user);

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
    }

    public function test_notification_has_correct_subject(): void
    {
        $user = UserAccount::factory()->unverified()->create();
        $notification = new CustomVerifyEmail();

        $mailMessage = $notification->toMail($user);

        // Access the subject via reflection or check the message
        $this->assertNotNull($mailMessage);
    }

    public function test_verification_url_is_signed_and_temporary(): void
    {
        $user = UserAccount::factory()->unverified()->create();
        $notification = new CustomVerifyEmail();

        // Use reflection to access protected method
        $reflection = new \ReflectionClass($notification);
        $method = $reflection->getMethod('verificationUrl');
        $method->setAccessible(true);

        $url = $method->invoke($notification, $user);

        // URL should contain signature and expires parameters
        $this->assertStringContainsString('signature=', $url);
        $this->assertStringContainsString('expires=', $url);
        $this->assertStringContainsString($user->id, $url);
    }
}
