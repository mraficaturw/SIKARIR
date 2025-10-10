<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_account_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_account_id')->constrained('user_accounts')->onDelete('cascade');
            $table->foreignId('internjob_id')->constrained('internjobs')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_account_id', 'internjob_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_account_favorites');
    }
};