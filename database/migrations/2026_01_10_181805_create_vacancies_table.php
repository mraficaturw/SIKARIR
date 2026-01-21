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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');

            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('location');
            $table->string('type')->default('Internship');
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->text('description');
            $table->text('responsibility')->nullable();
            $table->text('qualifications')->nullable();
            $table->date('deadline')->nullable();
            $table->string('category')->nullable();
            $table->string('apply_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
