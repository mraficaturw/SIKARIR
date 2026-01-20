<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if company_id column already exists (fresh migration scenario)
        if (Schema::hasColumn('internjobs', 'company_id')) {
            // Column already exists (from initial migration), skip adding it
            // Also skip data migration since there's no old data to migrate

            // Just clean up old columns if they exist
            if (Schema::hasColumn('internjobs', 'company')) {
                Schema::table('internjobs', function (Blueprint $table) {
                    $table->dropColumn(['company', 'logo']);
                });
            }
            return;
        }

        // Step 1: Add company_id column (nullable for now)
        Schema::table('internjobs', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained('companies')->nullOnDelete();
        });

        // Step 2: Auto-migrate existing company data
        // Get unique company names from internjobs
        $existingCompanies = DB::table('internjobs')
            ->select('company', 'logo')
            ->whereNotNull('company')
            ->distinct()
            ->get();

        foreach ($existingCompanies as $companyData) {
            // Check if company already exists in companies table
            $existingCompany = DB::table('companies')
                ->where('company_name', $companyData->company)
                ->first();

            if (!$existingCompany) {
                // Create new company entry
                $companyId = DB::table('companies')->insertGetId([
                    'company_name' => $companyData->company,
                    'logo' => $companyData->logo,
                    'email' => 'info@' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $companyData->company)) . '.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $companyId = $existingCompany->id;
                // Update logo if company exists but has no logo
                if (!$existingCompany->logo && $companyData->logo) {
                    DB::table('companies')
                        ->where('id', $companyId)
                        ->update(['logo' => $companyData->logo]);
                }
            }

            // Update internjobs with the company_id
            DB::table('internjobs')
                ->where('company', $companyData->company)
                ->update(['company_id' => $companyId]);
        }

        // Step 3: Drop old columns if they exist
        if (Schema::hasColumn('internjobs', 'company')) {
            Schema::table('internjobs', function (Blueprint $table) {
                $table->dropColumn(['company', 'logo']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back old columns
        Schema::table('internjobs', function (Blueprint $table) {
            $table->string('company')->nullable()->after('title');
            $table->string('logo')->nullable()->after('deadline');
        });

        // Migrate data back from company relation
        $internjobs = DB::table('internjobs')
            ->join('companies', 'internjobs.company_id', '=', 'companies.id')
            ->select('internjobs.id', 'companies.company_name', 'companies.logo')
            ->get();

        foreach ($internjobs as $job) {
            DB::table('internjobs')
                ->where('id', $job->id)
                ->update([
                    'company' => $job->company_name,
                    'logo' => $job->logo,
                ]);
        }

        // Drop company_id column
        Schema::table('internjobs', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
