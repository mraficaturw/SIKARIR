<?php

namespace Tests\Feature\Auth;

use App\Models\PasswordChangeToken;
use App\Models\UserAccount;
use App\Notifications\VerifyPasswordChange;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Test: Profile Page
    // =========================================================================

    public function test_profile_page_is_displayed_for_verified_user(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->get(route('profile.show'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
    }

    public function test_profile_page_shows_user_favorites(): void
    {
        $user = $this->createVerifiedUser();
        $company = \App\Models\Company::factory()->create();
        $job = \App\Models\Internjob::factory()->forCompany($company)->create();

        $user->favorites()->attach($job->id);

        $response = $this->actingAsUser($user)->get(route('profile.show'));

        $response->assertStatus(200);
        $response->assertSee($job->title);
    }

    public function test_unverified_user_cannot_access_profile(): void
    {
        $user = $this->createUnverifiedUser();

        $response = $this->actingAsUser($user)->get(route('profile.show'));

        $response->assertRedirect(route('verification.notice'));
    }

    // =========================================================================
    // Test: Edit Profile Form
    // =========================================================================

    public function test_edit_profile_page_is_displayed(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
    }

    // =========================================================================
    // Test: Update Profile
    // =========================================================================

    public function test_user_can_update_name(): void
    {
        $user = $this->createVerifiedUser(['name' => 'Old Name']);

        $response = $this->actingAsUser($user)->post(route('profile.update'), [
            'name' => 'New Name',
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertEquals('New Name', $user->fresh()->name);
    }

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('supabase-avatar');

        $user = $this->createVerifiedUser();
        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $response = $this->actingAsUser($user)->post(route('profile.update'), [
            'name' => $user->name,
            'avatar' => $file,
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertNotNull($user->fresh()->avatar);
    }

    public function test_avatar_upload_validates_file_type(): void
    {
        $user = $this->createVerifiedUser();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAsUser($user)->post(route('profile.update'), [
            'name' => $user->name,
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_avatar_upload_validates_file_size(): void
    {
        $user = $this->createVerifiedUser();
        // Create a file larger than 5MB
        $file = UploadedFile::fake()->image('avatar.jpg')->size(6000);

        $response = $this->actingAsUser($user)->post(route('profile.update'), [
            'name' => $user->name,
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    // =========================================================================
    // Test: Change Password Form
    // =========================================================================

    public function test_change_password_page_is_displayed(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->get(route('profile.change-password'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.change-password');
    }

    // =========================================================================
    // Test: Change Password Process
    // =========================================================================

    public function test_user_can_request_password_change(): void
    {
        Notification::fake();

        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->post(route('profile.change-password.post'), [
            'current_password' => 'Password123!',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        // Token should be created
        $this->assertDatabaseHas('password_change_tokens', [
            'user_id' => $user->id,
        ]);

        // Notification should be sent
        Notification::assertSentTo($user, VerifyPasswordChange::class);
    }

    public function test_change_password_validates_current_password(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->post(route('profile.change-password.post'), [
            'current_password' => 'WrongPassword123!',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_verify_password_change_with_valid_token(): void
    {
        $user = $this->createVerifiedUser();
        $newHashedPassword = Hash::make('NewPassword123!');

        $token = PasswordChangeToken::create([
            'user_id' => $user->id,
            'new_password' => $newHashedPassword,
            'token' => 'valid-test-token',
            'expires_at' => Carbon::now()->addHour(),
        ]);

        $response = $this->actingAsUser($user)->get(route('password.verify', ['token' => 'valid-test-token']));

        // Password should be updated
        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
        // Token should be deleted
        $this->assertDatabaseMissing('password_change_tokens', ['token' => 'valid-test-token']);
    }

    public function test_verify_password_change_fails_with_expired_token(): void
    {
        $user = $this->createVerifiedUser();
        $originalPassword = $user->password;

        PasswordChangeToken::create([
            'user_id' => $user->id,
            'new_password' => Hash::make('NewPassword123!'),
            'token' => 'expired-token',
            'expires_at' => Carbon::now()->subHour(), // Expired
        ]);

        $response = $this->actingAsUser($user)->get(route('password.verify', ['token' => 'expired-token']));

        // Password should NOT be updated
        $this->assertEquals($originalPassword, $user->fresh()->password);
        $response->assertSessionHasErrors();
    }

    public function test_verify_password_change_fails_with_invalid_token(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->get(route('password.verify', ['token' => 'nonexistent-token']));

        $response->assertSessionHasErrors();
    }
}
