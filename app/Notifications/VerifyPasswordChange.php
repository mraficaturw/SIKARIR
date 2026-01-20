<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * ============================================================================
 * NOTIFIKASI: VERIFIKASI PERUBAHAN PASSWORD
 * ============================================================================
 * Class notifikasi ini mengirim email untuk mengkonfirmasi perubahan password.
 * Ini adalah fitur keamanan tambahan - password baru tidak langsung aktif
 * sampai user mengklik link verifikasi di email.
 * 
 * Alur penggunaan:
 * 1. User submit form ganti password (password lama + password baru)
 * 2. Sistem validasi dan simpan token ke database
 * 3. Notifikasi ini dikirim dengan link verifikasi
 * 4. User klik link → password baru jadi aktif
 * 
 * Keamanan:
 * - Token acak 64 karakter
 * - Expires dalam 60 menit
 * - Satu user hanya bisa punya 1 token aktif
 * 
 * Dipanggil dari: ProfileController::changePassword()
 * ============================================================================
 */
class VerifyPasswordChange extends Notification
{
    /**
     * Trait untuk queue (notifikasi bisa dikirim secara async)
     * Ini meningkatkan performance - user tidak perlu menunggu email terkirim
     */
    use Queueable;

    /** @var string Token verifikasi yang akan dikirim di URL */
    protected string $token;

    /**
     * -------------------------------------------------------------------------
     * Constructor
     * -------------------------------------------------------------------------
     * Inisialisasi notifikasi dengan token verifikasi.
     * Token ini nanti akan digunakan untuk membuat URL verifikasi.
     * 
     * @param string $token Token acak 64 karakter dari ProfileController
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * -------------------------------------------------------------------------
     * Channel Pengiriman
     * -------------------------------------------------------------------------
     * Menentukan melalui channel apa notifikasi akan dikirim.
     * Saat ini hanya email, tapi bisa ditambah SMS, push notification, dll.
     * 
     * Channel yang tersedia di Laravel:
     * - 'mail': Email (SMTP, Mailgun, dll)
     * - 'database': Simpan di database
     * - 'broadcast': WebSocket/Pusher
     * - 'slack': Slack notification
     * 
     * @param object $notifiable User yang menerima notifikasi
     * @return array Daftar channel yang digunakan
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * -------------------------------------------------------------------------
     * Format Email
     * -------------------------------------------------------------------------
     * Method ini mendefinisikan tampilan dan isi email yang dikirim.
     * 
     * Struktur email:
     * - Subject: "Verifikasi Perubahan Password - SIKARIR"
     * - Greeting: "Halo {nama user}!"
     * - Body: Informasi tentang permintaan perubahan password
     * - Action: Tombol "Konfirmasi Perubahan Password"
     * - Footer: Info expiry dan instruksi keamanan
     * 
     * @param object $notifiable User yang menerima notifikasi
     * @return MailMessage Object yang merepresentasikan email
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Generate URL verifikasi dengan token
        // Route 'password.verify' didefinisikan di routes/web.php
        $url = route('password.verify', ['token' => $this->token]);

        return (new MailMessage)
            // Subject email
            ->subject('Verifikasi Perubahan Password - SIKARIR')

            // Salam pembuka dengan nama user
            ->greeting('Halo ' . $notifiable->name . '!')

            // Penjelasan tentang email ini
            ->line('Kami menerima permintaan untuk mengubah password akun SIKARIR Anda.')

            // Instruksi untuk user
            ->line('Klik tombol di bawah untuk mengkonfirmasi perubahan password:')

            // Tombol CTA
            ->action('Konfirmasi Perubahan Password', $url)

            // Info waktu expired
            ->line('Link ini akan kadaluarsa dalam 60 menit.')

            // Peringatan keamanan (penting!)
            ->line('Jika Anda tidak meminta perubahan password ini, abaikan email ini. Password Anda tidak akan berubah.')

            // Salam penutup
            ->salutation('Salam hangat, Tim SIKARIR');
    }

    /**
     * -------------------------------------------------------------------------
     * Format Array (untuk Database/Broadcast)
     * -------------------------------------------------------------------------
     * Method ini digunakan jika notifikasi disimpan ke database
     * atau dikirim melalui broadcast channel.
     * 
     * Saat ini tidak digunakan karena hanya via email,
     * tapi disediakan untuk kompatibilitas.
     * 
     * @param object $notifiable User yang menerima notifikasi
     * @return array Data notifikasi dalam format array
     */
    public function toArray(object $notifiable): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
