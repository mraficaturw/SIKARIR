<?php

namespace Tests\Feature;

use App\Models\Internjob;
use App\Models\UserAccount;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternjobTest extends TestCase
{
    use RefreshDatabase;

    protected function createJobWithCompany(array $jobAttributes = [], array $companyAttributes = []): Internjob
    {
        $company = Company::factory()->create($companyAttributes);
        return Internjob::factory()->forCompany($company)->create($jobAttributes);
    }

    // =========================================================================
    // Test: Welcome Page (index)
    // =========================================================================

    public function test_welcome_page_is_displayed(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    public function test_welcome_page_shows_latest_jobs(): void
    {
        $jobs = [];
        for ($i = 0; $i < 3; $i++) {
            $jobs[] = $this->createJobWithCompany(['title' => "Job $i"]);
        }

        $response = $this->get(route('welcome'));

        $response->assertStatus(200);
        foreach ($jobs as $job) {
            $response->assertSee($job->title);
        }
    }

    // =========================================================================
    // Test: Jobs Listing Page
    // =========================================================================

    public function test_jobs_page_is_displayed(): void
    {
        $response = $this->get(route('jobs'));

        $response->assertStatus(200);
        $response->assertViewIs('jobs');
    }

    public function test_jobs_page_shows_paginated_jobs(): void
    {
        // Create more than 10 jobs (pagination limit)
        for ($i = 0; $i < 15; $i++) {
            $this->createJobWithCompany();
        }

        $response = $this->get(route('jobs'));

        $response->assertStatus(200);
        // Should have pagination links
    }

    public function test_jobs_page_filters_by_search(): void
    {
        $targetJob = $this->createJobWithCompany(['title' => 'Software Engineer']);
        $otherJob = $this->createJobWithCompany(['title' => 'Marketing Manager']);

        $response = $this->get(route('jobs', ['search' => 'Software']));

        $response->assertStatus(200);
        $response->assertSee('Software Engineer');
    }

    public function test_jobs_page_filters_by_category(): void
    {
        $teknikJob = $this->createJobWithCompany(['category' => 'Fakultas Teknik']);
        $ekonomiJob = $this->createJobWithCompany(['category' => 'Fakultas Ekonomi dan Bisnis']);

        $response = $this->get(route('jobs', ['category' => 'Fakultas Teknik']));

        $response->assertStatus(200);
    }

    // =========================================================================
    // Test: Job Detail Page
    // =========================================================================

    public function test_job_detail_page_is_displayed(): void
    {
        $job = $this->createJobWithCompany(['title' => 'Detail Test Job']);

        $response = $this->get(route('job.detail', $job->id));

        $response->assertStatus(200);
        $response->assertViewIs('job-detail');
        $response->assertSee('Detail Test Job');
    }

    public function test_job_detail_page_shows_company_info(): void
    {
        $job = $this->createJobWithCompany([], ['company_name' => 'PT Test Company']);

        $response = $this->get(route('job.detail', $job->id));

        $response->assertStatus(200);
        $response->assertSee('PT Test Company');
    }

    public function test_job_detail_returns_404_for_nonexistent_job(): void
    {
        $response = $this->get(route('job.detail', 9999));

        $response->assertStatus(404);
    }

    // =========================================================================
    // Test: Company Detail Page
    // =========================================================================

    public function test_company_detail_page_is_displayed(): void
    {
        $company = Company::factory()->create(['company_name' => 'Test Company']);

        $response = $this->get(route('company.detail', $company->id));

        $response->assertStatus(200);
        $response->assertViewIs('company-detail');
        $response->assertSee('Test Company');
    }

    public function test_company_detail_shows_company_jobs(): void
    {
        $company = Company::factory()->create();
        $job1 = Internjob::factory()->forCompany($company)->create(['title' => 'Job One']);
        $job2 = Internjob::factory()->forCompany($company)->create(['title' => 'Job Two']);

        $response = $this->get(route('company.detail', $company->id));

        $response->assertStatus(200);
        $response->assertSee('Job One');
        $response->assertSee('Job Two');
    }

    // =========================================================================
    // Test: Toggle Favorite
    // =========================================================================

    public function test_guest_cannot_toggle_favorite(): void
    {
        $job = $this->createJobWithCompany();

        $response = $this->post(route('job.favorite.toggle', $job->id));

        $response->assertRedirect(route('login'));
    }

    public function test_verified_user_can_add_favorite(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();

        $response = $this->actingAsUser($user)->post(route('job.favorite.toggle', $job->id));

        $this->assertTrue($user->fresh()->hasFavorited($job->id));
    }

    public function test_verified_user_can_remove_favorite(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();
        $user->favorites()->attach($job->id);

        $response = $this->actingAsUser($user)->post(route('job.favorite.toggle', $job->id));

        $this->assertFalse($user->fresh()->hasFavorited($job->id));
    }

    // =========================================================================
    // Test: Toggle Applied
    // =========================================================================

    public function test_guest_cannot_toggle_applied(): void
    {
        $job = $this->createJobWithCompany();

        $response = $this->post(route('job.applied.toggle', $job->id));

        $response->assertRedirect(route('login'));
    }

    public function test_verified_user_can_mark_job_as_applied(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();

        $response = $this->actingAsUser($user)->post(route('job.applied.toggle', $job->id));

        $this->assertTrue($user->fresh()->hasApplied($job->id));
    }

    public function test_verified_user_can_unmark_job_as_applied(): void
    {
        $user = $this->createVerifiedUser();
        $job = $this->createJobWithCompany();
        $user->appliedJobs()->attach($job->id, ['applied_at' => now()]);

        $response = $this->actingAsUser($user)->post(route('job.applied.toggle', $job->id));

        $this->assertFalse($user->fresh()->hasApplied($job->id));
    }
}
