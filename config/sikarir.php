<?php

/**
 * ============================================================================
 * KONFIGURASI SIKARIR
 * ============================================================================
 * File konfigurasi untuk data statis aplikasi SIKARIR.
 * Sentralisasi data yang digunakan di banyak tempat.
 * ============================================================================
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Daftar Fakultas
    |--------------------------------------------------------------------------
    | Daftar fakultas yang tersedia untuk kategorisasi lowongan.
    | Setiap fakultas memiliki label dan icon Font Awesome.
    |
    | Penggunaan: config('sikarir.faculties')
    */
    'faculties' => [
        'Fakultas Teknik' => [
            'label' => 'Fakultas Teknik',
            'icon' => 'fa-cogs',
        ],
        'Fakultas Ekonomi dan Bisnis' => [
            'label' => 'Fakultas Ekonomi dan Bisnis',
            'icon' => 'fa-chart-line',
        ],
        'Fakultas Ilmu Komputer' => [
            'label' => 'Fakultas Ilmu Komputer',
            'icon' => 'fa-laptop-code',
        ],
        'Fakultas Hukum' => [
            'label' => 'Fakultas Hukum',
            'icon' => 'fa-gavel',
        ],
        'Fakultas Kesehatan' => [
            'label' => 'Fakultas Kesehatan',
            'icon' => 'fa-stethoscope',
        ],
        'Fakultas Pertanian' => [
            'label' => 'Fakultas Pertanian',
            'icon' => 'fa-seedling',
        ],
        'Fakultas Ilmu Sosial dan Politik' => [
            'label' => 'Fakultas Ilmu Sosial dan Politik',
            'icon' => 'fa-users',
        ],
        'Fakultas Keguruan dan Ilmu Pendidikan' => [
            'label' => 'Fakultas Keguruan dan Ilmu Pendidikan',
            'icon' => 'fa-chalkboard-teacher',
        ],
        'Fakultas Agama Islam' => [
            'label' => 'Fakultas Agama Islam',
            'icon' => 'fa-mosque',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Pagination
    |--------------------------------------------------------------------------
    | Nilai default untuk pagination di seluruh aplikasi.
    */
    'pagination' => [
        'jobs_per_page' => 10,
        'welcome_jobs_limit' => 6,
    ],
];
