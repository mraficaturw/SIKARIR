<?php

namespace Tests\Feature\Auth;

use App\Models\UserAccount;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Test: Verification Notice Page
    // =========================================================================

    public function test_verification_notice_page_is_displayed_for_unverified_user(): void
    {
        $user = $this->createUnverifiedUser();

        $response = $this->actingAsUser($user)->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-email');
    }

    public function test_verified_user_accessing_protected_route_is_not_redirected(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->get(route('profile.show'));

        $response->assertStatus(200);
    }

    // =========================================================================
    // Test: Email Verification Process
    // =========================================================================

    public function test_email_can_be_verified_with_valid_signed_url(): void
    {
        $user = $this->createUnverifiedUser();

        // Generate signed verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAsUser($user)->get($verificationUrl);

        // User should be verified now
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('welcome'));
    }

    public function test_email_verification_fails_with_invalid_hash(): void
    {
        $user = $this->createUnverifiedUser();

        $invalidUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => 'invalid-hash']
        );

        $response = $this->actingAsUser($user)->get($invalidUrl);

        // Should fail - email still unverified
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        $response->assertStatus(403);
    }

    public function test_already_verified_user_gets_info_message(): void
    {
        $user = $this->createVerifiedUser();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAsUser($user)->get($verificationUrl);

        // Should redirect with info message
        $response->assertRedirect(route('welcome'));
        $response->assertSessionHas('info');
    }

    // =========================================================================
    // Test: Resend Verification Email
    // =========================================================================

    public function test_verification_email_can_be_resent(): void
    {
        Notification::fake();

        $user = $this->createUnverifiedUser();

        $response = $this->actingAsUser($user)->post(route('verification.send'));

        Notification::assertSentTo($user, CustomVerifyEmail::class);
        $response->assertSessionHas('message');
    }

    public function test_resend_is_throttled(): void
    {
        $user = $this->createUnverifiedUser();

        // Send 6 requests (exceeds throttle limit of 6 per minute)
        for ($i = 0; $i < 6; $i++) {
            $this->actingAsUser($user)->post(route('verification.send'));
        }

        // 7th request should be throttled
        $response = $this->actingAsUser($user)->post(route('verification.send'));

        $response->assertStatus(429);
    }

    // =========================================================================
    // Test: Guest Access
    // =========================================================================

    public function test_guest_cannot_access_verification_notice(): void
    {
        $response = $this->get(route('verification.notice'));

        $response->assertRedirect(route('login'));
    }
}
