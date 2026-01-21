<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CustomVerifyEmail;

/**
 * ============================================================================
 * MODEL USER ACCOUNT
 * ============================================================================
 * Model ini merepresentasikan akun pengguna (mahasiswa) dalam database.
 * Digunakan untuk autentikasi, profil, dan tracking aktivitas lowongan.
 * 
 * Interface yang diimplementasi:
 * - MustVerifyEmail: Memaksa verifikasi email sebelum akses penuh
 * 
 * Trait yang digunakan:
 * - HasFactory: Untuk factory dan seeder
 * - Notifiable: Untuk mengirim notifikasi (email, dll)
 * 
 * Guard: user_accounts (berbeda dengan admin yang menggunakan guard 'admin')
 * 
 * Tabel: user_accounts
 * ============================================================================
 */
class UserAccount extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * -------------------------------------------------------------------------
     * Boot Method - Model Events
     * -------------------------------------------------------------------------
     * Method boot() dipanggil saat model pertama kali di-load.
     * Digunakan untuk mendaftarkan event listener pada model.
     * 
     * Event yang ditangani:
     * - deleting: Hapus avatar dari Supabase Storage saat user dihapus
     */
    protected static function boot()
    {
        parent::boot();

        // Event listener: saat user akan dihapus
        // Ini mencegah file avatar menumpuk di storage
        static::deleting(function ($user) {
            // Hapus file avatar dari Supabase jika ada
            if ($user->avatar) {
                Storage::disk('supabase-avatar')->delete($user->avatar);
            }
        });
    }

    /**
     * -------------------------------------------------------------------------
     * Kolom yang Bisa Diisi (Mass Assignment)
     * -------------------------------------------------------------------------
     * Daftar kolom yang boleh diisi melalui create() atau update().
     * Kolom sensitif seperti id, email_verified_at tidak termasuk.
     */
    protected $fillable = [
        'name',      // Nama lengkap pengguna
        'email',     // Alamat email (unik, digunakan untuk login)
        'password',  // Password ter-hash
        'avatar',    // Path file avatar di Supabase Storage
    ];

    /**
     * -------------------------------------------------------------------------
     * Kolom yang Disembunyikan
     * -------------------------------------------------------------------------
     * Kolom ini tidak akan muncul saat model dikonversi ke array/JSON.
     * Penting untuk keamanan agar password tidak terekspos.
     */
    protected $hidden = [
        'password',       // Password hash tidak boleh terlihat
        'remember_token', // Token "remember me" untuk keamanan
    ];

    /**
     * -------------------------------------------------------------------------
     * Type Casting
     * -------------------------------------------------------------------------
     * Konversi otomatis tipe data saat mengambil/menyimpan data.
     * 
     * 'hashed' pada password: otomatis hash saat di-set
     * (tanpa perlu Hash::make() secara manual)
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Konversi ke Carbon
            'password' => 'hashed',            // Auto-hash saat assign
        ];
    }

    /**
     * -------------------------------------------------------------------------
     * Accessor: URL Avatar
     * -------------------------------------------------------------------------
     * Menghasilkan URL lengkap avatar dari Supabase Storage.
     * 
     * Path di database hanya menyimpan nama file, accessor ini
     * menggabungkannya dengan base URL dari config.
     * 
     * @return string|null URL lengkap avatar atau null jika tidak ada
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            // Ambil base URL dari konfigurasi filesystems
            $baseUrl = config('filesystems.disks.supabase-avatar.url');
            // Gabungkan dengan path avatar
            return rtrim($baseUrl, '/') . '/' . ltrim($this->avatar, '/');
        }
        return null;
    }

    /**
     * -------------------------------------------------------------------------
     * Relasi: Lowongan yang Difavoritkan
     * -------------------------------------------------------------------------
     * Relasi Many-to-Many melalui pivot table 'user_account_favorites'.
     * Satu user bisa memfavoritkan banyak lowongan.
     * 
     * withTimestamps(): Otomatis isi created_at dan updated_at di pivot
     * 
     * Contoh penggunaan:
     * $user->favorites                  // Collection Vacancy
     * $user->favorites()->attach(1)     // Tambah favorit
     * $user->favorites()->detach(1)     // Hapus favorit
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(
            Vacancy::class,               // Model terkait
            'user_account_favorites',     // Nama pivot table
            'user_account_id',            // Foreign key untuk model ini
            'vacancy_id'                  // Foreign key untuk model terkait
        )->withTimestamps();              // Include timestamps di pivot
    }

    /**
     * -------------------------------------------------------------------------
     * Relasi: Lowongan yang Dilamar
     * -------------------------------------------------------------------------
     * Relasi Many-to-Many melalui pivot table 'user_account_applied'.
     * Satu user bisa melamar ke banyak lowongan.
     * 
     * Pivot table menyimpan:
     * - applied_at: timestamp kapan user melamar
     * - created_at, updated_at: timestamps otomatis
     * 
     * Contoh penggunaan:
     * $user->appliedJobs                              // Collection Vacancy
     * $user->appliedJobs()->attach(1, ['applied_at' => now()]) // Tambah
     * $user->appliedJobs->first()->pivot->applied_at  // Akses waktu apply
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appliedJobs()
    {
        return $this->belongsToMany(
            Vacancy::class,               // Model terkait
            'user_account_applied',       // Nama pivot table
            'user_account_id',            // Foreign key untuk model ini
            'vacancy_id'                  // Foreign key untuk model terkait
        )->withPivot('applied_at')        // Include kolom applied_at
            ->withTimestamps();              // Include timestamps di pivot
    }

    /**
     * -------------------------------------------------------------------------
     * Helper: Cek Apakah Sudah Memfavoritkan
     * -------------------------------------------------------------------------
     * Method helper untuk mengecek apakah user sudah memfavoritkan
     * lowongan tertentu. Berguna untuk menentukan state tombol favorit.
     * 
     * @param int $jobId ID lowongan yang dicek
     * @return bool True jika sudah difavoritkan, false jika belum
     */
    public function hasFavorited($jobId): bool
    {
        // Cek apakah ID ada dalam collection favorites
        return $this->favorites->contains('id', $jobId);
    }

    /**
     * -------------------------------------------------------------------------
     * Helper: Cek Apakah Sudah Melamar
     * -------------------------------------------------------------------------
     * Method helper untuk mengecek apakah user sudah melamar ke
     * lowongan tertentu. Berguna untuk menentukan state tombol apply.
     * 
     * @param int $jobId ID lowongan yang dicek
     * @return bool True jika sudah melamar, false jika belum
     */
    public function hasApplied($jobId): bool
    {
        // Cek apakah ID ada dalam collection appliedJobs
        return $this->appliedJobs->contains('id', $jobId);
    }

    /**
     * -------------------------------------------------------------------------
     * Override: Kirim Email Verifikasi
     * -------------------------------------------------------------------------
     * Override method bawaan Laravel untuk menggunakan notifikasi custom.
     * CustomVerifyEmail mengirim email dengan format dan bahasa Indonesia.
     * 
     * Method ini dipanggil:
     * - Setelah registrasi baru
     * - Saat user request kirim ulang email verifikasi
     * 
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        // Gunakan CustomVerifyEmail (bukan default VerifyEmail Laravel)
        $this->notify(new CustomVerifyEmail);
    }
}
