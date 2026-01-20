<?php

namespace Tests\Feature\Middleware;

use App\Models\UserAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureEmailVerifiedTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        // Try to access a protected route that uses EnsureEmailVerified
        $response = $this->get(route('profile.show'));

        $response->assertRedirect(route('login'));
    }

    public function test_unverified_user_is_redirected_to_verification_notice(): void
    {
        $user = $this->createUnverifiedUser();

        $response = $this->actingAsUser($user)->get(route('profile.show'));

        $response->assertRedirect(route('verification.notice'));
        // Note: Session message may vary based on middleware implementation
    }

    public function test_verified_user_can_access_protected_route(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->get(route('profile.show'));

        $response->assertStatus(200);
    }
}
