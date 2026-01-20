<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * ============================================================================
 * NOTIFIKASI: VERIFIKASI EMAIL CUSTOM
 * ============================================================================
 * Class notifikasi ini menggantikan verifikasi email default Laravel
 * dengan versi yang dikustomisasi untuk SIKARIR.
 * 
 * Kustomisasi:
 * - Subject email dalam Bahasa Indonesia
 * - Pesan menggunakan Bahasa Indonesia yang ramah
 * - Informasi tentang SIKARIR di body email
 * - Link verifikasi expires dalam 60 menit
 * 
 * Kapan notifikasi ini dikirim:
 * - Setelah user berhasil registrasi
 * - Ketika user request kirim ulang email verifikasi
 * 
 * Dikonfigurasi di: UserAccount::sendEmailVerificationNotification()
 * ============================================================================
 */
class CustomVerifyEmail extends BaseVerifyEmail
{
    /**
     * -------------------------------------------------------------------------
     * Membangun Pesan Email
     * -------------------------------------------------------------------------
     * Method ini override dari parent class untuk mengkustomisasi
     * tampilan dan isi email verifikasi.
     * 
     * Struktur email:
     * - Subject: "Verifikasi Email - SIKARIR"
     * - Greeting: "Halo!"
     * - Body: Selamat datang + instruksi verifikasi
     * - Action: Tombol "Verifikasi Email Saya"
     * - Footer: Info expiry dan instruksi jika tidak daftar
     * 
     * @param string $url URL verifikasi yang sudah di-generate
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            // Subject email yang muncul di inbox
            ->subject('Verifikasi Email - SIKARIR')

            // Salam pembuka
            ->greeting('Halo!')

            // Paragraph pertama: Selamat datang
            ->line('Selamat datang di **SIKARIR** - Platform pencarian magang terbaik untuk mahasiswa!')

            // Paragraph kedua: Instruksi verifikasi
            ->line('Kami sangat senang Anda bergabung dengan komunitas kami. Untuk memulai, mohon verifikasi alamat email Anda dengan mengklik tombol di bawah ini.')

            // Tombol CTA (Call to Action)
            ->action('Verifikasi Email Saya', $url)

            // Info waktu expired (60 menit)
            ->line('Link verifikasi ini akan kadaluarsa dalam 60 menit.')

            // Peringatan keamanan
            ->line('Jika Anda tidak membuat akun di SIKARIR, abaikan email ini.')

            // Salam penutup
            ->salutation('Salam hangat, Tim SIKARIR');
    }

    /**
     * -------------------------------------------------------------------------
     * Generate URL Verifikasi
     * -------------------------------------------------------------------------
     * Method ini override untuk mengkustomisasi URL verifikasi.
     * 
     * URL yang dihasilkan:
     * - Signed URL (mengandung signature untuk keamanan)
     * - Temporary (expires setelah waktu tertentu)
     * - Berisi ID user dan hash email
     * 
     * Format URL:
     * /email/verify/{id}/{hash}?expires=xxx&signature=xxx
     * 
     * @param mixed $notifiable User yang akan menerima notifikasi
     * @return string Signed URL yang valid selama 60 menit
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            // Nama route untuk verifikasi (didefinisikan di routes/web.php)
            'verification.verify',

            // Waktu expired: sekarang + 60 menit
            // Nilai 60 menit bisa diubah di config/auth.php (verification.expire)
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),

            // Parameter URL
            [
                'id' => $notifiable->getKey(),                        // ID user
                'hash' => sha1($notifiable->getEmailForVerification()), // Hash email
            ]
        );
    }
}
