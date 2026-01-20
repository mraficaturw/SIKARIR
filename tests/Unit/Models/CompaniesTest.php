<?php

namespace Tests\Unit\Models;

use App\Models\Internjob;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompaniesTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Test: Accessor - Logo URL
    // =========================================================================

    /**
     * @group integration
     * Note: This test requires actual Supabase credentials and is skipped in CI
     */
    public function test_logo_url_returns_storage_url_when_logo_exists(): void
    {
        $this->markTestSkipped('Requires Supabase credentials - run with: php artisan test --group=integration');
    }

    public function test_logo_url_returns_default_when_no_logo(): void
    {
        $company = Company::factory()->create([
            'logo' => null,
        ]);

        // Should return default asset
        $this->assertStringContainsString('img/com-logo-1.jpg', $company->logo_url);
    }

    // =========================================================================
    // Test: Relations
    // =========================================================================

    public function test_internjobs_relation_returns_all_company_jobs(): void
    {
        $company = Company::factory()->create();

        // Create multiple jobs for this company
        Internjob::factory()->count(3)->forCompany($company)->create();

        $this->assertCount(3, $company->internjobs);
    }

    public function test_company_can_have_zero_jobs(): void
    {
        $company = Company::factory()->create();

        $this->assertCount(0, $company->internjobs);
    }

    // =========================================================================
    // Test: Fillable Attributes
    // =========================================================================

    public function test_fillable_attributes_are_mass_assignable(): void
    {
        $data = [
            'company_name' => 'PT Test Indonesia',
            'logo' => 'logos/test.webp',
            'official_website' => 'https://test.com',
            'email' => 'hr@test.com',
            'phone' => '+62812345678',
            'address' => 'Jakarta, Indonesia',
            'company_description' => 'Test company description',
        ];

        $company = Company::create($data);

        $this->assertEquals('PT Test Indonesia', $company->company_name);
        $this->assertEquals('hr@test.com', $company->email);
    }
}
