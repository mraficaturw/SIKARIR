<?php

namespace Tests\Feature\Auth;

use App\Models\UserAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Test: Show Register Form
    // =========================================================================

    public function test_register_page_is_displayed(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    // =========================================================================
    // Test: Successful Registration
    // =========================================================================

    public function test_user_can_register_with_valid_data(): void
    {
        Event::fake([Registered::class]);

        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'test@student.unsika.ac.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        // User should be created
        $this->assertDatabaseHas('user_accounts', [
            'name' => 'Test User',
            'email' => 'test@student.unsika.ac.id',
        ]);

        // Registered event should be fired
        Event::assertDispatched(Registered::class);
    }

    public function test_user_is_logged_in_after_registration(): void
    {
        Event::fake([Registered::class]);

        $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'newuser@student.unsika.ac.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertAuthenticated('user_accounts');
    }

    public function test_user_is_redirected_to_verification_notice_after_registration(): void
    {
        Event::fake([Registered::class]);

        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'redirect@student.unsika.ac.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_password_is_hashed_when_stored(): void
    {
        Event::fake([Registered::class]);

        $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'hash@student.unsika.ac.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = UserAccount::where('email', 'hash@student.unsika.ac.id')->first();
        $this->assertNotEquals('Password123!', $user->password);
        $this->assertTrue(Hash::check('Password123!', $user->password));
    }

    // =========================================================================
    // Test: Validation - Name
    // =========================================================================

    public function test_registration_requires_name(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => '',
            'email' => 'test@student.unsika.ac.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('name');
    }

    // =========================================================================
    // Test: Validation - Email
    // =========================================================================

    public function test_registration_requires_email(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => '',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_requires_valid_email_format(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_email_must_be_unique(): void
    {
        UserAccount::factory()->create([
            'email' => 'existing@student.unsika.ac.id',
        ]);

        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'existing@student.unsika.ac.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // =========================================================================
    // Test: Validation - Password
    // =========================================================================

    public function test_registration_requires_password(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'test@student.unsika.ac.id',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_password_must_be_confirmed(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'test@student.unsika.ac.id',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword123!',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_password_minimum_length(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'test@student.unsika.ac.id',
            'password' => 'Pass1!',
            'password_confirmation' => 'Pass1!',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_password_requires_mixed_case(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'test@student.unsika.ac.id',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_password_requires_number(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'test@student.unsika.ac.id',
            'password' => 'PasswordTest!',
            'password_confirmation' => 'PasswordTest!',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_password_requires_symbol(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'test@student.unsika.ac.id',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
