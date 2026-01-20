<?php

namespace Tests\Unit\Models;

use App\Models\Internjob;
use App\Models\UserAccount;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Helper Setup
    // =========================================================================

    protected function createCompanyAndJob(): Internjob
    {
        $company = Company::factory()->create();
        return Internjob::factory()->forCompany($company)->create();
    }

    // =========================================================================
    // Test: Accessor - Avatar URL
    // =========================================================================

    public function test_avatar_url_returns_full_url_when_avatar_exists(): void
    {
        $user = UserAccount::factory()->create([
            'avatar' => 'avatars/test.webp',
        ]);

        // Avatar URL should contain the path
        $this->assertNotNull($user->avatar_url);
        $this->assertStringContainsString('avatars/test.webp', $user->avatar_url);
    }

    public function test_avatar_url_returns_null_when_no_avatar(): void
    {
        $user = UserAccount::factory()->create([
            'avatar' => null,
        ]);

        $this->assertNull($user->avatar_url);
    }

    // =========================================================================
    // Test: hasFavorited Helper
    // =========================================================================

    public function test_has_favorited_returns_true_when_job_is_favorited(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createCompanyAndJob();

        // Add to favorites
        $user->favorites()->attach($job->id);

        $this->assertTrue($user->hasFavorited($job->id));
    }

    public function test_has_favorited_returns_false_when_job_is_not_favorited(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createCompanyAndJob();

        $this->assertFalse($user->hasFavorited($job->id));
    }

    // =========================================================================
    // Test: hasApplied Helper
    // =========================================================================

    public function test_has_applied_returns_true_when_job_is_applied(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createCompanyAndJob();

        // Mark as applied with timestamp
        $user->appliedJobs()->attach($job->id, ['applied_at' => now()]);

        $this->assertTrue($user->hasApplied($job->id));
    }

    public function test_has_applied_returns_false_when_job_is_not_applied(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createCompanyAndJob();

        $this->assertFalse($user->hasApplied($job->id));
    }

    // =========================================================================
    // Test: Relations
    // =========================================================================

    public function test_favorites_relation_returns_correct_jobs(): void
    {
        $user = $this->createVerifiedUser();
        $job1 = $this->createCompanyAndJob();
        $job2 = $this->createCompanyAndJob();

        $user->favorites()->attach([$job1->id, $job2->id]);

        $this->assertCount(2, $user->favorites);
        $this->assertTrue($user->favorites->contains($job1));
        $this->assertTrue($user->favorites->contains($job2));
    }

    public function test_applied_jobs_relation_includes_pivot_data(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createCompanyAndJob();
        $appliedAt = now();

        $user->appliedJobs()->attach($job->id, ['applied_at' => $appliedAt]);

        $appliedJob = $user->appliedJobs->first();
        $this->assertNotNull($appliedJob);
        $this->assertNotNull($appliedJob->pivot->applied_at);
    }

    // =========================================================================
    // Test: Password Hashing (Casts)
    // =========================================================================

    public function test_password_is_automatically_hashed(): void
    {
        $user = UserAccount::factory()->create([
            'password' => 'PlainPassword123!',
        ]);

        // Password should not be stored as plain text
        $this->assertNotEquals('PlainPassword123!', $user->password);
        // Should be hashed (bcrypt starts with $2y$)
        $this->assertStringStartsWith('$2y$', $user->password);
    }

    // =========================================================================
    // Test: Email Verification
    // =========================================================================

    public function test_unverified_user_has_null_email_verified_at(): void
    {
        $user = UserAccount::factory()->unverified()->create();

        $this->assertNull($user->email_verified_at);
        $this->assertFalse($user->hasVerifiedEmail());
    }

    public function test_verified_user_has_email_verified_at(): void
    {
        $user = UserAccount::factory()->create();

        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue($user->hasVerifiedEmail());
    }
}
