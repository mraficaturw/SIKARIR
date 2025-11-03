<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\internjob;
use Carbon\Carbon;

class InternjobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Software Engineer Intern',
                'company' => 'Tech Corp',
                'location' => 'Jakarta',
                'salary_min' => 3000000,
                'salary_max' => 5000000,
                'description' => 'Develop software applications.',
                'responsibility' => 'Code, test, and debug software.',
                'qualifications' => 'Basic programming knowledge.',
                'deadline' => Carbon::now()->addDays(30),
                'logo' => null,
                'category' => 'Fakultas Ilmu Komputer',
                'apply_url' => 'https://techcorp.com/apply',
            ],
            [
                'title' => 'Marketing Intern',
                'company' => 'Business Inc',
                'location' => 'Surabaya',
                'salary_min' => 2500000,
                'salary_max' => 4000000,
                'description' => 'Assist in marketing campaigns.',
                'responsibility' => 'Create content and analyze data.',
                'qualifications' => 'Communication skills.',
                'deadline' => Carbon::now()->addDays(25),
                'logo' => null,
                'category' => 'Fakultas Ekonomi dan Bisnis',
                'apply_url' => 'https://techcorp.com/apply',
            ],
            [
                'title' => 'Mechanical Engineer Intern',
                'company' => 'Engineering Ltd',
                'location' => 'Bandung',
                'salary_min' => 3500000,
                'salary_max' => 5500000,
                'description' => 'Design mechanical systems.',
                'responsibility' => 'Assist in design and prototyping.',
                'qualifications' => 'Engineering background.',
                'deadline' => Carbon::now()->addDays(20),
                'logo' => null,
                'category' => 'Fakultas Teknik',
                'apply_url' => 'https://techcorp.com/apply',
            ],
            [
                'title' => 'Legal Intern',
                'company' => 'Law Firm',
                'location' => 'Yogyakarta',
                'salary_min' => 2000000,
                'salary_max' => 3500000,
                'description' => 'Assist in legal research.',
                'responsibility' => 'Research and draft documents.',
                'qualifications' => 'Law student.',
                'deadline' => Carbon::now()->addDays(15),
                'logo' => null,
                'category' => 'Fakultas Hukum',
                'apply_url' => 'https://techcorp.com/apply',
            ],
            [
                'title' => 'Nursing Intern',
                'company' => 'Hospital',
                'location' => 'Semarang',
                'salary_min' => 2800000,
                'salary_max' => 4500000,
                'description' => 'Assist in patient care.',
                'responsibility' => 'Monitor patients and assist nurses.',
                'qualifications' => 'Nursing student.',
                'deadline' => Carbon::now()->addDays(10),
                'logo' => null,
                'category' => 'Fakultas Kesehatan',
                'apply_url' => 'https://techcorp.com/apply',
            ],
            [
                'title' => 'Agricultural Intern',
                'company' => 'Farm Co',
                'location' => 'Medan',
                'salary_min' => 2200000,
                'salary_max' => 3800000,
                'description' => 'Research crop improvement.',
                'responsibility' => 'Conduct field research.',
                'qualifications' => 'Agriculture background.',
                'deadline' => Carbon::now()->addDays(35),
                'logo' => null,
                'category' => 'Fakultas Pertanian',
                'apply_url' => 'https://techcorp.com/apply',
            ],
            [
                'title' => 'Political Science Intern',
                'company' => 'NGO',
                'location' => 'Makassar',
                'salary_min' => 2400000,
                'salary_max' => 4000000,
                'description' => 'Analyze political data.',
                'responsibility' => 'Research and report.',
                'qualifications' => 'Social science degree.',
                'deadline' => Carbon::now()->addDays(40),
                'logo' => null,
                'category' => 'Fakultas Ilmu Sosial dan Politik',
                'apply_url' => 'https://techcorp.com/apply',
            ],
            [
                'title' => 'Education Intern',
                'company' => 'School',
                'location' => 'Palembang',
                'salary_min' => 2600000,
                'salary_max' => 4200000,
                'description' => 'Assist in teaching.',
                'responsibility' => 'Prepare lessons and tutor.',
                'qualifications' => 'Education student.',
                'deadline' => Carbon::now()->addDays(45),
                'logo' => null,
                'category' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'apply_url' => 'https://techcorp.com/apply',
            ],
            [
                'title' => 'Islamic Studies Intern',
                'company' => 'Religious Center',
                'location' => 'Pekanbaru',
                'salary_min' => 2300000,
                'salary_max' => 3900000,
                'description' => 'Research Islamic topics.',
                'responsibility' => 'Write articles and assist events.',
                'qualifications' => 'Islamic studies background.',
                'deadline' => Carbon::now()->addDays(50),
                'logo' => null,
                'category' => 'Fakultas Agama Islam',
                'apply_url' => 'https://techcorp.com/apply',
            ],
            [
                'title' => 'Data Analyst Intern',
                'company' => 'Data Tech',
                'location' => 'Bali',
                'salary_min' => 3200000,
                'salary_max' => 5200000,
                'description' => 'Analyze data sets.',
                'responsibility' => 'Clean data and create reports.',
                'qualifications' => 'Statistics or computer science.',
                'deadline' => Carbon::now()->addDays(55),
                'logo' => null,
                'category' => 'Fakultas Ilmu Komputer',
                'apply_url' => 'https://techcorp.com/apply',
            ],
        ];

        foreach ($jobs as $job) {
            internjob::create($job);
        }
    }
}
