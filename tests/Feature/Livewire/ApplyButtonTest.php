<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ApplyButton;
use App\Models\Internjob;
use App\Models\UserAccount;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ApplyButtonTest extends TestCase
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

    public function test_apply_button_component_renders(): void
    {
        $job = $this->createJobWithCompany();

        Livewire::test(ApplyButton::class, ['jobId' => $job->id])
            ->assertStatus(200);
    }

    public function test_apply_button_with_apply_url(): void
    {
        $job = $this->createJobWithCompany();

        Livewire::test(ApplyButton::class, [
            'jobId' => $job->id,
            'applyUrl' => 'https://company.com/apply',
        ])
            ->assertSet('applyUrl', 'https://company.com/apply');
    }

    // =========================================================================
    // Test: Initial State
    // =========================================================================

    public function test_guest_sees_not_applied_state(): void
    {
        $job = $this->createJobWithCompany();

        Livewire::test(ApplyButton::class, ['jobId' => $job->id])
            ->assertSet('isApplied', false);
    }

    public function test_verified_user_sees_applied_state_if_previously_applied(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();
        $user->appliedJobs()->attach($job->id, ['applied_at' => now()]);

        Livewire::actingAs($user, 'user_accounts')
            ->test(ApplyButton::class, ['jobId' => $job->id])
            ->assertSet('isApplied', true);
    }

    // =========================================================================
    // Test: Toggle Action (Guest)
    // =========================================================================

    public function test_guest_is_redirected_to_login_on_toggle(): void
    {
        $job = $this->createJobWithCompany();

        Livewire::test(ApplyButton::class, ['jobId' => $job->id])
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
            ->test(ApplyButton::class, ['jobId' => $job->id])
            ->call('toggle')
            ->assertRedirect(route('verification.notice'));
    }

    // =========================================================================
    // Test: Toggle Action (Verified User)
    // =========================================================================

    public function test_verified_user_can_mark_as_applied(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();

        Livewire::actingAs($user, 'user_accounts')
            ->test(ApplyButton::class, ['jobId' => $job->id])
            ->assertSet('isApplied', false)
            ->call('toggle')
            ->assertSet('isApplied', true);

        $this->assertTrue($user->fresh()->hasApplied($job->id));
        // Verify timestamp is saved
        $this->assertNotNull($user->appliedJobs()->first()->pivot->applied_at);
    }

    public function test_verified_user_can_unmark_as_applied(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();
        $user->appliedJobs()->attach($job->id, ['applied_at' => now()]);

        Livewire::actingAs($user, 'user_accounts')
            ->test(ApplyButton::class, ['jobId' => $job->id])
            ->assertSet('isApplied', true)
            ->call('toggle')
            ->assertSet('isApplied', false);

        $this->assertFalse($user->fresh()->hasApplied($job->id));
    }
}
