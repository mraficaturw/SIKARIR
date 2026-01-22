<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * ============================================================================
 * MODEL COMPANY
 * ============================================================================
 * Model ini merepresentasikan data perusahaan dalam database.
 * Digunakan untuk menampilkan informasi perusahaan pada lowongan.
 * 
 * Relasi:
 * - hasMany: Vacancy (satu perusahaan bisa punya banyak lowongan)
 * 
 * Storage:
 * - Logo disimpan di Supabase Storage (disk: supabase)
 * 
 * Tabel: companies
 * ============================================================================
 */
class Company extends Model
{
    use HasFactory;

    /**
     * -------------------------------------------------------------------------
     * Nama Tabel
     * -------------------------------------------------------------------------
     * Nama tabel eksplisit karena Laravel akan menghasilkan 'company'
     * dari nama class, padahal tabel sebenarnya adalah 'companies'.
     */
    protected $table = 'companies';

    /**
     * -------------------------------------------------------------------------
     * Kolom yang Bisa Diisi (Mass Assignment)
     * -------------------------------------------------------------------------
     * Daftar kolom yang boleh diisi melalui create() atau update().
     */
    protected $fillable = [
        'company_name',        // Nama perusahaan (contoh: "PT ABC Indonesia")
        'logo',                // Path file logo di Supabase Storage
        'official_website',    // URL website resmi perusahaan
        'email',               // Alamat email kontak perusahaan
        'phone',               // Nomor telepon perusahaan
        'address',             // Alamat lengkap kantor perusahaan
        'company_description', // Deskripsi tentang perusahaan
    ];

    /**
     * -------------------------------------------------------------------------
     * Accessor: URL Logo
     * -------------------------------------------------------------------------
     * Menghasilkan URL lengkap logo dari Supabase Storage.
     * Jika logo tidak ada, mengembalikan gambar default.
     * 
     * Accessor ini memungkinkan akses: $company->logo_url
     * (Virtual attribute, tidak ada di database)
     * 
     * @return string URL lengkap logo atau gambar default
     */
    public function getLogoUrlAttribute(): string
    {
        // Jika perusahaan punya logo di storage
        if ($this->logo) {
            // Gunakan Supabase Storage untuk generate URL publik
            return Storage::disk('supabase')->url($this->logo);
        }
        // Jika tidak ada logo, gunakan gambar default dari public folder
        return asset('img/com-logo-1.jpg');
    }

    /**
     * -------------------------------------------------------------------------
     * Cached Query: Company Detail
     * -------------------------------------------------------------------------
     * Mengambil data company dengan relasi vacancies, dengan cache.
     * Cache akan expired sesuai TTL yang dikonfigurasi.
     * 
     * Contoh penggunaan:
     * Company::getCachedCompany($id);
     * 
     * @param int $id ID company yang akan diambil
     * @return \App\Models\Company|null
     */
    public static function getCachedCompany(int $id): ?Company
    {
        $cacheKey = config('sikarir.cache.keys.company_detail') . ':' . $id;
        $cacheTTL = config('sikarir.cache.ttl.companies', 3600);

        return cache()->remember($cacheKey, $cacheTTL, function () use ($id) {
            return static::with('vacancies')->find($id);
        });
    }

    /**
     * -------------------------------------------------------------------------
     * Clear Company Cache
     * -------------------------------------------------------------------------
     * Menghapus cache untuk company tertentu.
     * Dipanggil otomatis oleh Observer saat data berubah.
     * 
     * @param int $id ID company yang cache-nya akan dihapus
     * @return void
     */
    public static function clearCache(int $id): void
    {
        $cacheKey = config('sikarir.cache.keys.company_detail') . ':' . $id;
        cache()->forget($cacheKey);
    }

    /**
     * -------------------------------------------------------------------------
     * Relasi: Lowongan dari Perusahaan
     * -------------------------------------------------------------------------
     * Relasi One-to-Many ke model Vacancy.
     * Satu perusahaan bisa memiliki banyak lowongan.
     * 
     * Contoh penggunaan:
     * $company->vacancies              // Collection semua lowongan
     * $company->vacancies()->count()   // Jumlah lowongan aktif
     * $company->vacancies()->latest()  // Lowongan terbaru
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vacancies()
    {
        return $this->hasMany(Vacancy::class, 'company_id');
    }
}
