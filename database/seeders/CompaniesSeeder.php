<?php

namespace Database\Seeders;

use App\Models\companies;
use Illuminate\Database\Seeder;

/**
 * ============================================================================
 * SEEDER: COMPANIES
 * ============================================================================
 * Seeder untuk mengisi tabel companies dengan data dummy.
 * Logo default menggunakan gambar dari public folder SIKARIR.
 */
class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 10 perusahaan dummy
        companies::factory(10)->create();

        $this->command->info('✓ 10 companies berhasil di-seed!');
    }
}
