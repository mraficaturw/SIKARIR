<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Internjob;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * ============================================================================
 * FACTORY: INTERNJOB
 * ============================================================================
 * Factory untuk generate data dummy lowongan magang/kerja.
 * Company bisa menggunakan data existing atau buat baru.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Internjob>
 */
class InternjobFactory extends Factory
{
    /**
     * Model yang di-generate oleh factory ini.
     */
    protected $model = Internjob::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $faker = Faker::create(config('app.faker_locale', 'en_US'));

        // Daftar judul posisi lowongan
        $jobTitles = [
            'Software Engineer Intern',
            'Frontend Developer Intern',
            'Backend Developer Intern',
            'Mobile Developer Intern',
            'Data Analyst Intern',
            'UI/UX Designer Intern',
            'DevOps Engineer Intern',
            'Machine Learning Intern',
            'Quality Assurance Intern',
            'Product Manager Intern',
            'Business Analyst Intern',
            'Digital Marketing Intern',
            'Graphic Designer Intern',
            'Content Writer Intern',
            'Human Resource Intern',
            'Finance Intern',
            'Accounting Intern',
            'Legal Intern',
            'Administrative Intern',
            'Research Assistant Intern',
        ];

        // Daftar lokasi di Indonesia
        $locations = [
            'Jakarta, Indonesia',
            'Bandung, Indonesia',
            'Surabaya, Indonesia',
            'Yogyakarta, Indonesia',
            'Semarang, Indonesia',
            'Malang, Indonesia',
            'Medan, Indonesia',
            'Makassar, Indonesia',
            'Bali, Indonesia',
            'Remote (Indonesia)',
            'Hybrid - Jakarta',
            'Hybrid - Bandung',
        ];

        // Daftar tipe pekerjaan
        $types = [
            'Internship',
            'Full-time',
            'Part-time',
            'Contract',
            'Freelance',
        ];

        // Daftar kategori (fakultas UNSIKA)
        $categories = [
            'Fakultas Teknik',
            'Fakultas Ekonomi dan Bisnis',
            'Fakultas Ilmu Komputer',
            'Fakultas Hukum',
            'Fakultas Kesehatan',
            'Fakultas Pertanian',
            'Fakultas Ilmu Sosial dan Politik',
            'Fakultas Keguruan dan Ilmu Pendidikan',
            'Fakultas Agama Islam',
        ];

        // Generate salary range
        $salaryMin = $faker->randomElement([0, 1000000, 1500000, 2000000, 2500000, 3000000]);
        $salaryMax = $salaryMin > 0 ? $salaryMin + $faker->numberBetween(500000, 3000000) : 0;

        // Coba gunakan company yang sudah ada, jika tidak ada buat baru
        $company = Company::inRandomOrder()->first();
        if (!$company) {
            $company = Company::factory()->create();
        }

        return [
            'title' => $faker->randomElement($jobTitles),
            'company_id' => $company->id,
            'location' => $faker->randomElement($locations),
            'type' => $faker->randomElement($types),
            'salary_min' => $salaryMin,
            'salary_max' => $salaryMax,
            'description' => $this->generateDescription($faker),
            'responsibility' => $this->generateResponsibility($faker),
            'qualifications' => $this->generateQualifications($faker),
            'deadline' => $faker->dateTimeBetween('+1 week', '+3 months'),
            'category' => $faker->randomElement($categories),
            'apply_url' => $faker->optional(0.7)->url(),
        ];
    }

    /**
     * State untuk menggunakan company tertentu.
     */
    public function forCompany(Company $company): static
    {
        return $this->state(fn(array $attributes) => [
            'company_id' => $company->id,
        ]);
    }

    /**
     * State untuk lowongan dengan gaji tinggi.
     */
    public function highSalary(): static
    {
        return $this->state(fn(array $attributes) => [
            'salary_min' => 5000000,
            'salary_max' => 10000000,
        ]);
    }

    /**
     * State untuk lowongan yang sudah expired.
     */
    public function expired(): static
    {
        $faker = Faker::create(config('app.faker_locale', 'en_US'));

        return $this->state(fn(array $attributes) => [
            'deadline' => $faker->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }

    /**
     * Generate deskripsi lowongan yang realistis.
     */
    private function generateDescription($faker): string
    {
        $descriptions = [
            "Kami mencari kandidat yang bersemangat untuk bergabung dengan tim kami. " .
                "Dalam posisi ini, Anda akan mendapatkan kesempatan untuk belajar dan berkembang bersama profesional berpengalaman. " .
                "Kami menawarkan lingkungan kerja yang dinamis dan mendukung pertumbuhan karir Anda.",

            "Bergabunglah dengan tim inovatif kami! Anda akan bekerja pada proyek-proyek menarik yang berdampak nyata. " .
                "Kami mencari individu yang kreatif, proaktif, dan memiliki keinginan kuat untuk belajar hal baru. " .
                "Tersedia mentor yang akan membimbing Anda selama program berlangsung.",

            "Kesempatan magang yang luar biasa untuk mahasiswa atau fresh graduate! " .
                "Dapatkan pengalaman kerja nyata di industri yang kompetitif. " .
                "Kami menyediakan fasilitas lengkap dan suasana kerja yang menyenangkan.",
        ];

        return $faker->randomElement($descriptions);
    }

    /**
     * Generate tanggung jawab posisi.
     */
    private function generateResponsibility($faker): string
    {
        $responsibilities = [
            "- Membantu tim dalam pengembangan dan pemeliharaan proyek\n" .
                "- Melakukan riset dan analisis untuk mendukung keputusan tim\n" .
                "- Berkolaborasi dengan anggota tim lain dalam menyelesaikan tugas\n" .
                "- Membuat dokumentasi dan laporan berkala\n" .
                "- Menghadiri meeting dan memberikan update progress",

            "- Berkontribusi aktif dalam pengembangan produk\n" .
                "- Membantu dalam proses testing dan quality assurance\n" .
                "- Mempelajari best practices dan standar industri\n" .
                "- Berpartisipasi dalam brainstorming dan problem solving\n" .
                "- Mendukung aktivitas operasional harian tim",
        ];

        return $faker->randomElement($responsibilities);
    }

    /**
     * Generate kualifikasi yang dibutuhkan.
     */
    private function generateQualifications($faker): string
    {
        $qualifications = [
            "- Mahasiswa aktif atau fresh graduate dari jurusan terkait\n" .
                "- IPK minimal 3.0 (skala 4.0)\n" .
                "- Memiliki kemampuan komunikasi yang baik\n" .
                "- Dapat bekerja secara individu maupun tim\n" .
                "- Bersedia magang selama minimal 3 bulan\n" .
                "- Memiliki motivasi tinggi untuk belajar",

            "- Mahasiswa semester 5 ke atas atau fresh graduate\n" .
                "- Memiliki pengetahuan dasar di bidang terkait\n" .
                "- Familiar dengan tools dan teknologi terkini\n" .
                "- Mampu beradaptasi dengan cepat di lingkungan kerja\n" .
                "- Memiliki kemampuan problem solving yang baik\n" .
                "- Detail-oriented dan mampu bekerja under pressure",
        ];

        return $faker->randomElement($qualifications);
    }
}
