<?php

namespace Database\Seeders;

use App\Models\companies;
use App\Models\Internjob;
use Illuminate\Database\Seeder;

/**
 * ============================================================================
 * SEEDER: INTERNJOB
 * ============================================================================
 * Seeder untuk mengisi tabel internjobs dengan data dummy.
 * Lowongan akan di-assign ke company yang sudah ada atau buat baru.
 */
class InternjobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua company yang ada
        $companies = companies::all();

        // Jika tidak ada company, buat dulu beberapa company
        if ($companies->isEmpty()) {
            $this->command->warn('Tidak ada companies, membuat 5 companies baru...');
            $companies = companies::factory(5)->create();
        }

        // Buat 3-5 lowongan untuk setiap company yang ada
        foreach ($companies as $company) {
            $jobCount = rand(3, 5);
            Internjob::factory($jobCount)
                ->forCompany($company)
                ->create();

            $this->command->info("  → {$jobCount} lowongan dibuat untuk {$company->company_name}");
        }

        // Tambahan: buat beberapa lowongan dengan company baru
        Internjob::factory(10)->create();

        $totalJobs = Internjob::count();
        $this->command->info("✓ Total {$totalJobs} lowongan berhasil di-seed!");
    }
}
