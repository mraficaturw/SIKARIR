<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

/**
 * ============================================================================
 * MODEL USER (ADMIN)
 * ============================================================================
 * Model ini merepresentasikan akun admin untuk Filament Admin Panel.
 * Berbeda dengan UserAccount yang digunakan untuk mahasiswa/pengguna biasa.
 * 
 * Interface yang diimplementasi:
 * - FilamentUser: Diperlukan untuk mengakses Filament Admin Panel
 * 
 * Guard: admin (terpisah dari guard user_accounts)
 * URL: /admin
 * 
 * Tabel: users
 * ============================================================================
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * -------------------------------------------------------------------------
     * Kolom yang Bisa Diisi (Mass Assignment)
     * -------------------------------------------------------------------------
     * Daftar kolom yang boleh diisi melalui create() atau update().
     */
    protected $fillable = [
        'name',      // Nama admin
        'email',     // Email admin (untuk login ke panel)
        'password',  // Password admin (ter-hash)
    ];

    /**
     * -------------------------------------------------------------------------
     * Kolom yang Disembunyikan
     * -------------------------------------------------------------------------
     * Kolom ini tidak akan muncul saat model dikonversi ke array/JSON.
     */
    protected $hidden = [
        'password',       // Password hash tidak boleh terlihat
        'remember_token', // Token "remember me" untuk keamanan
    ];

    /**
     * -------------------------------------------------------------------------
     * Cek Akses ke Filament Panel
     * -------------------------------------------------------------------------
     * Method ini menentukan apakah user boleh mengakses Filament panel.
     * Dipanggil setiap kali user mencoba masuk ke /admin.
     * 
     * Konfigurasi saat ini: Semua user admin boleh akses.
     * 
     * Bisa dikustomisasi untuk pembatasan, contoh:
     * - return $this->email === 'superadmin@sikarir.com';
     * - return $this->hasRole('admin');
     * - return str_ends_with($this->email, '@sikarir.com');
     * 
     * @param Panel $panel Instance Filament panel
     * @return bool True jika boleh akses, false jika tidak
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Semua user admin diizinkan mengakses panel
        // Ubah logic ini untuk membatasi akses berdasarkan role/permission
        return true;
    }
}
