<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * ============================================================================
 * FACTORY: COMPANY
 * ============================================================================
 * Factory untuk generate data dummy perusahaan.
 * Logo default menggunakan gambar dari public folder SIKARIR.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Model yang di-generate oleh factory ini.
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Daftar nama perusahaan Indonesia dummy
        $companyNames = [
            'PT Teknologi Nusantara',
            'PT Digital Kreasi Indonesia',
            'PT Solusi Data Prima',
            'PT Inovasi Madya Bangsa',
            'PT Karya Anak Bangsa',
            'PT Maju Bersama Indonesia',
            'PT Cerdas Teknologi',
            'PT Startup Muda Indonesia',
            'PT Kreasi Digital Utama',
            'PT Bina Talenta Bangsa',
            'CV Coding Indonesia',
            'PT Software House Nusantara',
            'PT Cloud Computing Indonesia',
            'PT Fintech Muda',
            'PT Edukasi Teknologi',
        ];

        return [
            'company_name' => fake()->randomElement($companyNames) . ' ' . fake()->numberBetween(1, 100),
            'logo' => null, // Default: null (akan pakai default logo dari accessor)
            'official_website' => fake()->optional(0.7)->url(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->optional(0.8)->phoneNumber(),
            'address' => fake()->optional(0.7)->address(),
            'company_description' => fake()->optional(0.6)->sentence(10),
        ];
    }

    /**
     * State untuk perusahaan dengan data lengkap.
     */
    public function complete(): static
    {
        return $this->state(fn(array $attributes) => [
            'official_website' => fake()->url(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'company_description' => fake()->sentence(15),
        ]);
    }
}
