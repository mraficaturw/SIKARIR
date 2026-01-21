<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

/**
 * ============================================================================
 * SEEDER: VACANCY
 * ============================================================================
 * Seeder untuk mengisi tabel vacancies dengan data dummy.
 * Lowongan akan di-assign ke company yang sudah ada atau buat baru.
 */
class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua company yang ada
        $companies = Company::all();

        // Jika tidak ada company, buat dulu beberapa company
        if ($companies->isEmpty()) {
            $this->command->warn('Tidak ada companies, membuat 5 companies baru...');
            $companies = Company::factory(5)->create();
        }

        // Buat lowongan variatif untuk setiap company
        foreach ($companies as $company) {
            // 1. Normal Range Salary Jobs (2-3 items)
            Vacancy::factory(rand(2, 3))->forCompany($company)->create();

            // 2. Unpaid Jobs (1-2 items)
            Vacancy::factory(rand(1, 2))->unpaid()->forCompany($company)->create();

            // 3. Min/Max Salary Only (1 item each potentially)
            if (rand(0, 1)) {
                Vacancy::factory()->minOnly()->forCompany($company)->create();
            }
            if (rand(0, 1)) {
                Vacancy::factory()->maxOnly()->forCompany($company)->create();
            }
        }

        // Tambahan: buat beberapa lowongan random
        Vacancy::factory(5)->create();

        $totalJobs = Vacancy::count();
        $this->command->info("✓ Total {$totalJobs} lowongan berhasil di-seed dengan variasi salary!");
    }
}
