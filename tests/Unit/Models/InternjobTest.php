<?php

namespace Tests\Unit\Models;

use App\Models\Internjob;
use App\Models\UserAccount;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternjobTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Test: Accessor - Logo URL
    // =========================================================================

    /**
     * @group integration
     * Note: This test requires actual Supabase credentials and is skipped in CI
     */
    public function test_logo_url_returns_company_logo_when_company_exists(): void
    {
        $this->markTestSkipped('Requires Supabase credentials - run with: php artisan test --group=integration');
    }

    public function test_logo_url_returns_default_when_company_has_no_logo(): void
    {
        $company = Company::factory()->create([
            'logo' => null,
        ]);
        $job = Internjob::factory()->forCompany($company)->create();

        // Should return default asset
        $this->assertStringContainsString('img/com-logo-1.jpg', $job->logo_url);
    }

    public function test_logo_url_returns_default_when_no_company(): void
    {
        // Create job with non-existent company_id
        $job = new Internjob([
            'title' => 'Test Job',
            'company_id' => 9999, // Non-existent
        ]);

        // Should return default
        $this->assertStringContainsString('img/com-logo-1.jpg', $job->logo_url);
    }

    // =========================================================================
    // Test: Relations
    // =========================================================================

    public function test_company_relation_returns_correct_company(): void
    {
        $company = Company::factory()->create([
            'company_name' => 'PT Test Company',
        ]);
        $job = Internjob::factory()->forCompany($company)->create();

        $this->assertEquals('PT Test Company', $job->company->company_name);
    }

    public function test_favored_by_relation_returns_users_who_favorited(): void
    {
        $company = Company::factory()->create();
        $job = Internjob::factory()->forCompany($company)->create();
        $user1 = UserAccount::factory()->create();
        $user2 = UserAccount::factory()->create();

        $user1->favorites()->attach($job->id);
        $user2->favorites()->attach($job->id);

        $this->assertCount(2, $job->favoredBy);
        $this->assertTrue($job->favoredBy->contains($user1));
        $this->assertTrue($job->favoredBy->contains($user2));
    }

    public function test_applied_by_relation_returns_users_who_applied(): void
    {
        $company = Company::factory()->create();
        $job = Internjob::factory()->forCompany($company)->create();
        $user = UserAccount::factory()->create();

        $user->appliedJobs()->attach($job->id, ['applied_at' => now()]);

        $this->assertCount(1, $job->appliedBy);
        $this->assertTrue($job->appliedBy->contains($user));
        $this->assertNotNull($job->appliedBy->first()->pivot->applied_at);
    }

    // =========================================================================
    // Test: Casts (Date & Decimal)
    // =========================================================================

    public function test_deadline_is_cast_to_carbon_date(): void
    {
        $company = Company::factory()->create();
        $job = Internjob::factory()->forCompany($company)->create([
            'deadline' => '2026-12-31',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $job->deadline);
    }

    public function test_salary_fields_are_cast_to_decimal(): void
    {
        $company = Company::factory()->create();
        $job = Internjob::factory()->forCompany($company)->create([
            'salary_min' => 1500000.50,
            'salary_max' => 3000000.75,
        ]);

        // Should be stored as string with 2 decimal places
        $this->assertEquals('1500000.50', $job->salary_min);
        $this->assertEquals('3000000.75', $job->salary_max);
    }
}
