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
        if (!Schema::hasTable('user_account_favorites')) {
            Schema::create('user_account_favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_account_id')->constrained('user_accounts')->onDelete('cascade');
                $table->foreignId('vacancy_id')->constrained('vacancies')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_account_id', 'vacancy_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_account_favorites');
    }
};
