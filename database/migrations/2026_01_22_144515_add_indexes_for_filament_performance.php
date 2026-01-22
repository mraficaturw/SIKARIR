<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vacancies', function (Blueprint $table) {
            // Index for company relation (used in joins and where clauses)
            $table->index('company_id', 'idx_vacancies_company_id');

            // Index for deadline sorting and filtering
            $table->index('deadline', 'idx_vacancies_deadline');

            // Index for category filtering
            $table->index('category', 'idx_vacancies_category');

            // Composite index for common sort combinations
            $table->index(['created_at', 'deadline'], 'idx_vacancies_created_deadline');
        });

        Schema::table('companies', function (Blueprint $table) {
            // Index for company name searching and sorting
            $table->index('company_name', 'idx_companies_name');

            // Index for email searching
            $table->index('email', 'idx_companies_email');

            // Index for sorting by creation date
            $table->index('created_at', 'idx_companies_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropIndex('idx_vacancies_company_id');
            $table->dropIndex('idx_vacancies_deadline');
            $table->dropIndex('idx_vacancies_category');
            $table->dropIndex('idx_vacancies_created_deadline');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex('idx_companies_name');
            $table->dropIndex('idx_companies_email');
            $table->dropIndex('idx_companies_created_at');
        });
    }
};
