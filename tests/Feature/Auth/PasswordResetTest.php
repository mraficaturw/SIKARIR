<?php

namespace Tests\Feature\Auth;

use App\Models\UserAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Test: Forgot Password Form
    // =========================================================================

    public function test_forgot_password_page_is_displayed(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    // =========================================================================
    // Test: Send Reset Link
    // =========================================================================

    public function test_reset_link_can_be_sent(): void
    {
        Notification::fake();

        $user = UserAccount::factory()->create([
            'email' => 'test@student.unsika.ac.id',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'test@student.unsika.ac.id',
        ]);

        // Password reset notification should be sent
        // Note: Laravel's default PasswordBroker sends its own notification
        $response->assertSessionHas('status');
    }

    public function test_reset_link_request_validates_email(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => '',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_reset_link_request_validates_email_format(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // =========================================================================
    // Test: Reset Password Form
    // =========================================================================

    public function test_reset_password_page_is_displayed(): void
    {
        $user = UserAccount::factory()->create();

        // Generate a token using Password facade
        $token = Password::broker('user_accounts')->createToken($user);

        $response = $this->get(route('password.reset', ['token' => $token]));

        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
    }

    // =========================================================================
    // Test: Password Reset Process
    // =========================================================================

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = UserAccount::factory()->create([
            'email' => 'reset@student.unsika.ac.id',
        ]);

        $token = Password::broker('user_accounts')->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'reset@student.unsika.ac.id',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        // User should be able to login with new password
        $user->refresh();
        $this->assertTrue(Hash::check('NewPassword123!', $user->password));
    }

    public function test_password_reset_fails_with_invalid_token(): void
    {
        $user = UserAccount::factory()->create([
            'email' => 'invalid@student.unsika.ac.id',
        ]);

        $response = $this->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => 'invalid@student.unsika.ac.id',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_password_reset_requires_matching_confirmation(): void
    {
        $user = UserAccount::factory()->create([
            'email' => 'confirm@student.unsika.ac.id',
        ]);

        $token = Password::broker('user_accounts')->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'confirm@student.unsika.ac.id',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'DifferentPassword123!',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
