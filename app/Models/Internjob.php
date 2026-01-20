<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ============================================================================
 * MODEL INTERNJOB
 * ============================================================================
 * Model ini merepresentasikan data lowongan kerja/magang dalam database.
 * Digunakan untuk menampilkan daftar lowongan dan detail lowongan.
 * 
 * Relasi:
 * - belongsTo: companies (setiap lowongan dimiliki oleh satu perusahaan)
 * - belongsToMany: UserAccount (lowongan bisa difavoritkan banyak user)
 * - belongsToMany: UserAccount (lowongan bisa dilamar banyak user)
 * 
 * Tabel: internjobs
 * ============================================================================
 */
class Internjob extends Model
{
    use HasFactory;

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
     * Menggabungkan logic yang sebelumnya duplikat di InternjobController
     * dan JobSearch Livewire component.
     * 
     * Pencarian dilakukan pada:
     * - Judul lowongan (title)
     * - Nama perusahaan (company_name via relasi)
     * - Deskripsi lowongan (description)
     * 
     * Contoh penggunaan:
     * Internjob::search($keyword, $category)->get();
     * Internjob::search($keyword)->paginate(10);
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
     * Relasi: Perusahaan Pemilik Lowongan
     * -------------------------------------------------------------------------
     * Setiap lowongan dimiliki oleh satu perusahaan.
     * Relasi BelongsTo ke model companies melalui company_id.
     * 
     * Contoh penggunaan:
     * $job->company->company_name  // "PT ABC Indonesia"
     * $job->company->logo_url      // URL logo perusahaan
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
     * Accessor ini memungkinkan akses: $job->logo_url
     * (tanpa harus $job->company->logo_url)
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
     * Relasi: Users yang Memfavoritkan
     * -------------------------------------------------------------------------
     * Relasi Many-to-Many melalui pivot table 'user_account_favorites'.
     * Satu lowongan bisa difavoritkan oleh banyak user.
     * 
     * Contoh penggunaan:
     * $job->favoredBy           // Collection UserAccount yang memfavoritkan
     * $job->favoredBy()->count() // Jumlah user yang memfavoritkan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoredBy()
    {
        return $this->belongsToMany(
            UserAccount::class,           // Model terkait
            'user_account_favorites',     // Nama pivot table
            'internjob_id',               // Foreign key di pivot untuk model ini
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
     * $job->appliedBy                     // Collection UserAccount yang melamar
     * $job->appliedBy->first()->pivot->applied_at // Waktu apply
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appliedBy()
    {
        return $this->belongsToMany(
            UserAccount::class,           // Model terkait
            'user_account_applied',       // Nama pivot table
            'internjob_id',               // Foreign key di pivot untuk model ini
            'user_account_id'             // Foreign key di pivot untuk model terkait
        )->withPivot('applied_at');       // Include kolom tambahan dari pivot
    }
}
