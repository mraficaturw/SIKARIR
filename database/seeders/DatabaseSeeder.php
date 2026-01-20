<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * ============================================================================
 * DATABASE SEEDER
 * ============================================================================
 * Main seeder yang menjalankan semua seeder lainnya.
 * Jalankan dengan: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('=== Memulai Database Seeding ===');

        // Seed Companies terlebih dahulu (dependency untuk Internjobs)
        $this->call(CompaniesSeeder::class);

        // Seed Internjobs (menggunakan companies yang sudah ada)
        $this->call(InternjobSeeder::class);

        $this->command->info('=== Database Seeding Selesai! ===');
    }
}
