<?php

namespace Tests\Feature\Livewire;

use App\Livewire\FavoriteButton;
use App\Models\Internjob;
use App\Models\UserAccount;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FavoriteButtonTest extends TestCase
{
    use RefreshDatabase;

    protected function createJobWithCompany(): Internjob
    {
        $company = Company::factory()->create();
        return Internjob::factory()->forCompany($company)->create();
    }

    // =========================================================================
    // Test: Component Renders
    // =========================================================================

    public function test_favorite_button_component_renders(): void
    {
        $job = $this->createJobWithCompany();

        Livewire::test(FavoriteButton::class, ['jobId' => $job->id])
            ->assertStatus(200);
    }

    // =========================================================================
    // Test: Initial State (Guest)
    // =========================================================================

    public function test_guest_sees_unfavorited_state(): void
    {
        $job = $this->createJobWithCompany();

        Livewire::test(FavoriteButton::class, ['jobId' => $job->id])
            ->assertSet('isFavorite', false);
    }

    // =========================================================================
    // Test: Initial State (Authenticated)
    // =========================================================================

    public function test_verified_user_sees_correct_initial_state(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();
        $user->favorites()->attach($job->id);

        Livewire::actingAs($user, 'user_accounts')
            ->test(FavoriteButton::class, ['jobId' => $job->id])
            ->assertSet('isFavorite', true);
    }

    public function test_unfavorited_job_shows_false(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();

        Livewire::actingAs($user, 'user_accounts')
            ->test(FavoriteButton::class, ['jobId' => $job->id])
            ->assertSet('isFavorite', false);
    }

    // =========================================================================
    // Test: Toggle Action (Guest)
    // =========================================================================

    public function test_guest_is_redirected_to_login_on_toggle(): void
    {
        $job = $this->createJobWithCompany();

        Livewire::test(FavoriteButton::class, ['jobId' => $job->id])
            ->call('toggle')
            ->assertRedirect(route('login'));
    }

    // =========================================================================
    // Test: Toggle Action (Unverified User)
    // =========================================================================

    public function test_unverified_user_is_redirected_to_verification(): void
    {
        $user = $this->createUnverifiedUser();
        $job = $this->createJobWithCompany();

        Livewire::actingAs($user, 'user_accounts')
            ->test(FavoriteButton::class, ['jobId' => $job->id])
            ->call('toggle')
            ->assertRedirect(route('verification.notice'));
    }

    // =========================================================================
    // Test: Toggle Action (Verified User)
    // =========================================================================

    public function test_verified_user_can_add_favorite(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();

        Livewire::actingAs($user, 'user_accounts')
            ->test(FavoriteButton::class, ['jobId' => $job->id])
            ->assertSet('isFavorite', false)
            ->call('toggle')
            ->assertSet('isFavorite', true);

        $this->assertTrue($user->fresh()->hasFavorited($job->id));
    }

    public function test_verified_user_can_remove_favorite(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();
        $user->favorites()->attach($job->id);

        Livewire::actingAs($user, 'user_accounts')
            ->test(FavoriteButton::class, ['jobId' => $job->id])
            ->assertSet('isFavorite', true)
            ->call('toggle')
            ->assertSet('isFavorite', false);

        $this->assertFalse($user->fresh()->hasFavorited($job->id));
    }
}
