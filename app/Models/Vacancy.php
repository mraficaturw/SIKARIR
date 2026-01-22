<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================================
 * MODEL VACANCY
 * ============================================================================
 * Model ini merepresentasikan data lowongan kerja/magang dalam database.
 * Digunakan untuk menampilkan daftar lowongan dan detail lowongan.
 * 
 * Relasi:
 * - belongsTo: companies (setiap lowongan dimiliki oleh satu perusahaan)
 * - belongsToMany: UserAccount (lowongan bisa difavoritkan banyak user)
 * - belongsToMany: UserAccount (lowongan bisa dilamar banyak user)
 * 
 * Tabel: vacancies
 * ============================================================================
 */
class Vacancy extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model ini.
     */
    protected $table = 'vacancies';

    /**
     * -------------------------------------------------------------------------
     * Kolom yang Bisa Diisi (Mass Assignment)
     * -------------------------------------------------------------------------
     * Daftar kolom yang boleh diisi melalui create() atau update().
     * Kolom lain akan diabaikan untuk keamanan.
     */
    protected $fillable = [
        'title',           // Judul posisi lowongan (contoh: "Software Engineer Intern")
        'company_id',      // ID perusahaan (foreign key ke tabel companies)
        'location',        // Lokasi penempatan kerja (contoh: "Jakarta, Indonesia")
        'type',            // Tipe pekerjaan (full-time, part-time, magang, dll)
        'salary_min',      // Gaji minimum dalam rupiah
        'salary_max',      // Gaji maksimum dalam rupiah
        'description',     // Deskripsi lengkap lowongan
        'responsibility',  // Tanggung jawab posisi ini
        'qualifications',  // Kualifikasi yang dibutuhkan
        'deadline',        // Batas waktu pendaftaran
        'category',        // Kategori berdasarkan fakultas
        'apply_url',       // Link untuk mendaftar (URL external)
    ];

    /**
     * -------------------------------------------------------------------------
     * Type Casting
     * -------------------------------------------------------------------------
     * Konversi otomatis tipe data saat mengambil/menyimpan data.
     * Memastikan format data konsisten di PHP.
     */
    protected $casts = [
        'deadline' => 'date',        // Konversi ke objek Carbon (tanggal)
        'salary_min' => 'decimal:2', // Konversi ke decimal dengan 2 angka desimal
        'salary_max' => 'decimal:2', // Konversi ke decimal dengan 2 angka desimal
    ];

    /**
     * -------------------------------------------------------------------------
     * Query Scope: Search & Filter
     * -------------------------------------------------------------------------
     * Scope untuk pencarian dan filter lowongan.
     * Menggabungkan logic yang sebelumnya duplikat di VacancyController
     * dan JobSearch Livewire component.
     * 
     * Pencarian dilakukan pada:
     * - Judul lowongan (title)
     * - Nama perusahaan (company_name via relasi)
     * - Deskripsi lowongan (description)
     * 
     * Contoh penggunaan:
     * Vacancy::search($keyword, $category)->get();
     * Vacancy::search($keyword)->paginate(10);
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search Kata kunci pencarian
     * @param string|null $category Filter berdasarkan kategori/fakultas
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, ?string $search = null, ?string $category = null)
    {
        // Filter berdasarkan kata kunci pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($cq) use ($search) {
                        $cq->where('company_name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan kategori fakultas
        if ($category) {
            $query->where('category', $category);
        }

        return $query;
    }

    /**
     * -------------------------------------------------------------------------
     * Cached Query: Latest Vacancies
     * -------------------------------------------------------------------------
     * Mengambil lowongan terbaru dengan cache.
     * Cache akan expired sesuai TTL yang dikonfigurasi.
     * 
     * Contoh penggunaan:
     * Vacancy::getCachedLatest($limit);
     * 
     * @param int $limit Jumlah lowongan yang diambil
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCachedLatest(int $limit = 6)
    {
        $cacheKey = config('sikarir.cache.keys.vacancy_latest') . ':' . $limit;
        $cacheTTL = config('sikarir.cache.ttl.vacancies', 3600);

        return cache()->remember($cacheKey, $cacheTTL, function () use ($limit) {
            return static::with('company')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * -------------------------------------------------------------------------
     * Cached Query: Category Counts
     * -------------------------------------------------------------------------
     * Menghitung jumlah lowongan per kategori dengan cache.
     * Cache akan expired sesuai TTL categories karena jarang berubah.
     * 
     * Contoh penggunaan:
     * Vacancy::getCachedCategoryCounts();
     * 
     * @return array Array dengan format ['Fakultas X' => count]
     */
    public static function getCachedCategoryCounts(): array
    {
        $cacheKey = config('sikarir.cache.keys.vacancy_category_counts');
        $cacheTTL = config('sikarir.cache.ttl.categories', 86400);

        return cache()->remember($cacheKey, $cacheTTL, function () {
            $faculties = config('sikarir.faculties', []);
            $counts = [];

            foreach ($faculties as $key => $data) {
                $counts[$key] = static::where('category', $key)->count();
            }

            return $counts;
        });
    }

    /**
     * -------------------------------------------------------------------------
     * Clear All Vacancy Caches
     * -------------------------------------------------------------------------
     * Menghapus semua cache yang terkait dengan vacancy.
     * Dipanggil otomatis oleh Observer saat data berubah.
     * 
     * @return void
     */
    public static function clearCache(): void
    {
        // Clear cache untuk latest vacancies dengan berbagai limit
        foreach ([6, 10, 12, 20] as $limit) {
            $key = config('sikarir.cache.keys.vacancy_latest') . ':' . $limit;
            cache()->forget($key);
        }

        // Clear category counts
        cache()->forget(config('sikarir.cache.keys.vacancy_category_counts'));

        // Clear search results cache (pattern-based clear tidak tersedia di database driver)
        // Untuk production dengan Redis, bisa gunakan cache()->tags()
    }

    /**
     * -------------------------------------------------------------------------
     * Relasi: Perusahaan Pemilik Lowongan
     * -------------------------------------------------------------------------
     * Setiap lowongan dimiliki oleh satu perusahaan.
     * Relasi BelongsTo ke model Company melalui company_id.
     * 
     * Contoh penggunaan:
     * $vacancy->company->company_name  // "PT ABC Indonesia"
     * $vacancy->company->logo_url      // URL logo perusahaan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * -------------------------------------------------------------------------
     * Accessor: URL Logo
     * -------------------------------------------------------------------------
     * Mengambil URL logo dari perusahaan yang terkait.
     * Jika perusahaan tidak punya logo, gunakan gambar default.
     * 
     * Accessor ini memungkinkan akses: $vacancy->logo_url
     * (tanpa harus $vacancy->company->logo_url)
     * 
     * @return string URL logo perusahaan
     */
    public function getLogoUrlAttribute(): string
    {
        // Cek apakah lowongan punya relasi company
        if ($this->company) {
            return $this->company->logo_url;
        }
        // Jika tidak ada, gunakan gambar default dari public folder
        return asset('img/com-logo-1.jpg');
    }

    /**
     * -------------------------------------------------------------------------
     * Accessor: Formatted Salary
     * -------------------------------------------------------------------------
     * Menampilkan salary dengan format yang tepat berdasarkan kondisi:
     * - Jika keduanya null → "Unpaid"
     * - Jika hanya salary_min terisi → "Rp X.XXX.XXX"
     * - Jika hanya salary_max terisi → "Rp X.XXX.XXX"
     * - Jika keduanya terisi → "Rp X.XXX.XXX - X.XXX.XXX"
     * 
     * Contoh penggunaan:
     * $vacancy->formatted_salary // "Rp 1.500.000 - 3.000.000" atau "Unpaid"
     * 
     * @return string Salary yang sudah diformat
     */
    public function getFormattedSalaryAttribute(): string
    {
        $min = $this->salary_min;
        $max = $this->salary_max;

        // Jika keduanya kosong/null/0, tampilkan "Unpaid"
        // empty() catches null, 0, "0", "" etc.
        if (empty($min) && empty($max)) {
            return 'Unpaid';
        }

        // Jika hanya salary_min yang terisi (dan > 0)
        if (!empty($min) && empty($max)) {
            return 'Rp ' . number_format((int)$min, 0, ',', '.');
        }

        // Jika hanya salary_max yang terisi (dan > 0)
        if (empty($min) && !empty($max)) {
            return 'Rp ' . number_format((int)$max, 0, ',', '.');
        }

        // Jika keduanya terisi, tampilkan range
        return 'Rp ' . number_format((int)$min, 0, ',', '.') . ' - ' . number_format((int)$max, 0, ',', '.');
    }

    /**
     * -------------------------------------------------------------------------
     * Relasi: Users yang Memfavoritkan
     * -------------------------------------------------------------------------
     * Relasi Many-to-Many melalui pivot table 'user_account_favorites'.
     * Satu lowongan bisa difavoritkan oleh banyak user.
     * 
     * Contoh penggunaan:
     * $vacancy->favoredBy           // Collection UserAccount yang memfavoritkan
     * $vacancy->favoredBy()->count() // Jumlah user yang memfavoritkan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoredBy()
    {
        return $this->belongsToMany(
            UserAccount::class,           // Model terkait
            'user_account_favorites',     // Nama pivot table
            'vacancy_id',                 // Foreign key di pivot untuk model ini
            'user_account_id'             // Foreign key di pivot untuk model terkait
        );
    }

    /**
     * -------------------------------------------------------------------------
     * Relasi: Users yang Melamar
     * -------------------------------------------------------------------------
     * Relasi Many-to-Many melalui pivot table 'user_account_applied'.
     * Satu lowongan bisa dilamar oleh banyak user.
     * 
     * Pivot table menyimpan kolom tambahan:
     * - applied_at: timestamp kapan user melamar
     * 
     * Contoh penggunaan:
     * $vacancy->appliedBy                     // Collection UserAccount yang melamar
     * $vacancy->appliedBy->first()->pivot->applied_at // Waktu apply
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appliedBy()
    {
        return $this->belongsToMany(
            UserAccount::class,           // Model terkait
            'user_account_applied',       // Nama pivot table
            'vacancy_id',                 // Foreign key di pivot untuk model ini
            'user_account_id'             // Foreign key di pivot untuk model terkait
        )->withPivot('applied_at');       // Include kolom tambahan dari pivot
    }
}
