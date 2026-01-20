<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ============================================================================
 * MODEL PASSWORD CHANGE TOKEN
 * ============================================================================
 * Model ini menyimpan token untuk verifikasi perubahan password.
 * Digunakan sebagai langkah keamanan tambahan - password baru tidak
 * langsung aktif sampai user verifikasi via email.
 * 
 * Alur penggunaan:
 * 1. User request ganti password di ProfileController::changePassword()
 * 2. Token dibuat dan disimpan di tabel ini
 * 3. Email berisi link verifikasi dikirim ke user
 * 4. User klik link → ProfileController::verifyPasswordChange()
 * 5. Password diupdate dan token dihapus
 * 
 * Fitur keamanan:
 * - Token random 64 karakter (tidak bisa ditebak)
 * - Expires dalam 60 menit (tidak bisa dipakai selamanya)
 * - Satu user hanya bisa punya 1 token aktif (token lama dihapus)
 * - Token dihapus setelah digunakan (one-time use)
 * 
 * Tabel: password_change_tokens
 * ============================================================================
 */
class PasswordChangeToken extends Model
{
    use HasFactory;

    /**
     * -------------------------------------------------------------------------
     * Kolom yang Bisa Diisi (Mass Assignment)
     * -------------------------------------------------------------------------
     * Daftar kolom yang boleh diisi melalui create() atau update().
     */
    protected $fillable = [
        'user_id',       // ID user yang meminta ganti password (foreign key)
        'new_password',  // Password baru yang sudah di-hash (belum aktif)
        'token',         // Token acak untuk verifikasi (64 karakter)
        'expires_at',    // Waktu expired token
    ];

    /**
     * -------------------------------------------------------------------------
     * Type Casting
     * -------------------------------------------------------------------------
     * Konversi otomatis kolom expires_at ke objek Carbon.
     * Ini memudahkan operasi perbandingan tanggal.
     */
    protected $casts = [
        'expires_at' => 'datetime', // Otomatis jadi Carbon instance
    ];

    /**
     * -------------------------------------------------------------------------
     * Relasi: User Pemilik Token
     * -------------------------------------------------------------------------
     * Setiap token dimiliki oleh satu user (UserAccount).
     * Digunakan untuk mengambil data user saat verifikasi.
     * 
     * Contoh penggunaan:
     * $token->user->name  // Nama user pemilik token
     * $token->user->email // Email user pemilik token
     * 
     * @return BelongsTo Relasi ke UserAccount
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_id');
    }

    /**
     * -------------------------------------------------------------------------
     * Cek Apakah Token Sudah Expired
     * -------------------------------------------------------------------------
     * Method helper untuk mengecek apakah token masih valid.
     * Token dianggap expired jika waktu sekarang sudah melewati expires_at.
     * 
     * Contoh penggunaan:
     * if ($token->isExpired()) {
     *     $token->delete();
     *     return error('Token sudah kadaluarsa');
     * }
     * 
     * @return bool True jika sudah expired, false jika masih valid
     */
    public function isExpired(): bool
    {
        // isPast() adalah method Carbon yang return true jika waktu < now
        return $this->expires_at->isPast();
    }
}
