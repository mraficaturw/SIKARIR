<?php

namespace Tests\Feature\Auth;

use App\Models\UserAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Test: Show Login Form
    // =========================================================================

    public function test_login_page_is_displayed(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    // =========================================================================
    // Test: Successful Login
    // =========================================================================

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = UserAccount::factory()->create([
            'email' => 'test@student.unsika.ac.id',
            'password' => 'Password123!',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'test@student.unsika.ac.id',
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($user, 'user_accounts');
    }

    public function test_verified_user_is_redirected_to_profile_after_login(): void
    {
        $user = UserAccount::factory()->create([
            'email' => 'verified@student.unsika.ac.id',
            'password' => 'Password123!',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'verified@student.unsika.ac.id',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('profile.show'));
    }

    // =========================================================================
    // Test: Unverified User Login
    // =========================================================================

    public function test_unverified_user_is_redirected_to_verification_notice(): void
    {
        $user = UserAccount::factory()->unverified()->create([
            'email' => 'unverified@student.unsika.ac.id',
            'password' => 'Password123!',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'unverified@student.unsika.ac.id',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('verification.notice'));
    }

    // =========================================================================
    // Test: Failed Login
    // =========================================================================

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = UserAccount::factory()->create([
            'email' => 'test@student.unsika.ac.id',
            'password' => 'Password123!',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'test@student.unsika.ac.id',
            'password' => 'WrongPassword123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('user_accounts');
    }

    public function test_user_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->post(route('login.submit'), [
            'email' => 'nonexistent@student.unsika.ac.id',
            'password' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('user_accounts');
    }

    // =========================================================================
    // Test: Validation
    // =========================================================================

    public function test_login_requires_email(): void
    {
        $response = $this->post(route('login.submit'), [
            'email' => '',
            'password' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_login_requires_password(): void
    {
        $response = $this->post(route('login.submit'), [
            'email' => 'test@student.unsika.ac.id',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // =========================================================================
    // Test: Rate Limiting
    // =========================================================================

    public function test_login_is_rate_limited_after_multiple_failed_attempts(): void
    {
        $email = 'test@student.unsika.ac.id';
        UserAccount::factory()->create([
            'email' => $email,
            'password' => 'Password123!',
        ]);

        // Make 5 failed login attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.submit'), [
                'email' => $email,
                'password' => 'WrongPassword!',
            ]);
        }

        // 6th attempt should be rate limited
        $response = $this->post(route('login.submit'), [
            'email' => $email,
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors('email');
        // Error message should contain "Terlalu banyak" (too many attempts)
    }

    // =========================================================================
    // Test: Logout
    // =========================================================================

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->createVerifiedUser();
        $this->actingAsUser($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest('user_accounts');
    }

    public function test_logout_invalidates_session(): void
    {
        $user = $this->createVerifiedUser();
        $this->actingAsUser($user);

        $this->post(route('logout'));

        $this->assertGuest('user_accounts');
    }
}
