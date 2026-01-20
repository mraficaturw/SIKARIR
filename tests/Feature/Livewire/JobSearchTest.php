<?php

namespace Tests\Feature\Livewire;

use App\Livewire\JobSearch;
use App\Models\Internjob;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class JobSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function createJobWithCompany(array $jobAttributes = []): Internjob
    {
        $company = Company::factory()->create();
        return Internjob::factory()->forCompany($company)->create($jobAttributes);
    }

    // =========================================================================
    // Test: Component Renders
    // =========================================================================

    public function test_job_search_component_renders(): void
    {
        Livewire::test(JobSearch::class)
            ->assertStatus(200);
    }

    // =========================================================================
    // Test: Search Functionality
    // =========================================================================

    public function test_search_filters_jobs_by_title(): void
    {
        $matchingJob = $this->createJobWithCompany(['title' => 'Software Engineer']);
        $nonMatchingJob = $this->createJobWithCompany(['title' => 'Marketing Manager']);

        Livewire::test(JobSearch::class)
            ->set('search', 'Software')
            ->assertSee('Software Engineer')
            ->assertDontSee('Marketing Manager');
    }

    public function test_search_filters_jobs_by_company_name(): void
    {
        $company1 = Company::factory()->create(['company_name' => 'Tech Company']);
        $company2 = Company::factory()->create(['company_name' => 'Finance Corp']);

        Internjob::factory()->forCompany($company1)->create(['title' => 'Tech Job']);
        Internjob::factory()->forCompany($company2)->create(['title' => 'Finance Job']);

        Livewire::test(JobSearch::class)
            ->set('search', 'Tech Company')
            ->assertSee('Tech Job')
            ->assertDontSee('Finance Job');
    }

    public function test_search_resets_pagination(): void
    {
        // Create 15 jobs
        for ($i = 0; $i < 15; $i++) {
            $this->createJobWithCompany();
        }

        Livewire::test(JobSearch::class)
            ->set('search', 'keyword')
            ->assertSet('page', 1);
    }

    // =========================================================================
    // Test: Category Filter
    // =========================================================================

    public function test_category_filter_works(): void
    {
        $teknikJob = $this->createJobWithCompany(['category' => 'Fakultas Teknik']);
        $ekonomiJob = $this->createJobWithCompany(['category' => 'Fakultas Ekonomi dan Bisnis']);

        Livewire::test(JobSearch::class)
            ->set('category', 'Fakultas Teknik')
            ->assertSee($teknikJob->title)
            ->assertDontSee($ekonomiJob->title);
    }

    public function test_category_change_resets_pagination(): void
    {
        Livewire::test(JobSearch::class)
            ->set('category', 'Fakultas Teknik')
            ->assertSet('page', 1);
    }

    // =========================================================================
    // Test: Combined Filters
    // =========================================================================

    public function test_search_and_category_can_be_combined(): void
    {
        $matchingJob = $this->createJobWithCompany([
            'title' => 'Software Engineer',
            'category' => 'Fakultas Ilmu Komputer',
        ]);
        $wrongCategory = $this->createJobWithCompany([
            'title' => 'Software Developer',
            'category' => 'Fakultas Teknik',
        ]);

        Livewire::test(JobSearch::class)
            ->set('search', 'Software')
            ->set('category', 'Fakultas Ilmu Komputer')
            ->assertSee('Software Engineer')
            ->assertDontSee('Software Developer');
    }

    // =========================================================================
    // Test: Computed Property - Faculties
    // =========================================================================

    public function test_faculties_property_returns_array(): void
    {
        $component = Livewire::test(JobSearch::class);

        // The faculties should be passed to view
        $component->assertViewHas('faculties');
    }
}
