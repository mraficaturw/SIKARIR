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
 * - hasMany: Internjob (satu perusahaan bisa punya banyak lowongan)
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
     * Relasi: Lowongan dari Perusahaan
     * -------------------------------------------------------------------------
     * Relasi One-to-Many ke model Internjob.
     * Satu perusahaan bisa memiliki banyak lowongan.
     * 
     * Contoh penggunaan:
     * $company->internjobs              // Collection semua lowongan
     * $company->internjobs()->count()   // Jumlah lowongan aktif
     * $company->internjobs()->latest()  // Lowongan terbaru
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function internjobs()
    {
        return $this->hasMany(Internjob::class, 'company_id');
    }
}
