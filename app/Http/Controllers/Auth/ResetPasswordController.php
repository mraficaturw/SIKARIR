<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

/**
 * ============================================================================
 * RESET PASSWORD CONTROLLER
 * ============================================================================
 * Controller ini menangani proses reset password (langkah kedua).
 * User mengklik link dari email → masukkan password baru.
 * 
 * Catatan: Controller ini memiliki beberapa method yang mungkin redundant:
 * - showResetForm() dan reset() untuk flow reset password standar
 * - changePassword() dan verifyPasswordChange() untuk flow alternatif
 *   menggunakan cache (bukan database token)
 * 
 * Flow standar password reset menggunakan ForgotPasswordController.
 * ============================================================================
 */
class ResetPasswordController extends Controller
{
    /**
     * -------------------------------------------------------------------------
     * Menampilkan Form Reset Password
     * -------------------------------------------------------------------------
     * Method ini menampilkan halaman form dimana user bisa memasukkan
     * password baru. User sampai ke halaman ini dari link di email.
     * 
     * @return \Illuminate\View\View Halaman form reset password
     */
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    /**
     * -------------------------------------------------------------------------
     * Proses Perubahan Password (Flow Alternatif)
     * -------------------------------------------------------------------------
     * Method ini menangani perubahan password dengan verifikasi email.
     * Ini adalah flow alternatif yang menggunakan cache untuk menyimpan token.
     * 
     * Alur proses:
     * 1. Validasi email, password lama, dan password baru
     * 2. Verifikasi password lama benar
     * 3. Generate token dan simpan di cache (24 jam)
     * 4. Kirim email verifikasi
     * 5. User klik link → verifyPasswordChange() dipanggil
     * 
     * @param Request $request Data request berisi email, old_password, password
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status
     */
    public function changePassword(Request $request)
    {
        // -----------------------------------------------------------------
        // LANGKAH 1: Validasi Input
        // -----------------------------------------------------------------
        $request->validate([
            'email' => 'required|email|exists:user_accounts,email',
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Cari user berdasarkan email
        $user = UserAccount::where('email', $request->email)->first();

        // -----------------------------------------------------------------
        // LANGKAH 2: Verifikasi Password Lama
        // -----------------------------------------------------------------
        // Pastikan user memasukkan password lama yang benar
        // Ini mencegah orang lain mengganti password jika device tidak aman
        if (!Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => 'Email atau Password Lama tidak sesuai.',
            ]);
        }

        // -----------------------------------------------------------------
        // LANGKAH 3: Generate Token dan Simpan di Cache
        // -----------------------------------------------------------------
        // Token acak 64 karakter
        $token = Str::random(64);

        // Key cache unik untuk setiap user
        $cacheKey = 'password_change_' . $user->id;

        // Simpan data di cache selama 24 jam (1440 menit)
        // Data berisi: token, password baru (sudah di-hash), waktu expired
        cache([$cacheKey => [
            'token' => $token,
            'new_password' => Hash::make($request->password),
            'expires_at' => Carbon::now()->addHours(24),
        ]], 1440);

        // -----------------------------------------------------------------
        // LANGKAH 4: Kirim Email Verifikasi
        // -----------------------------------------------------------------
        try {
            Mail::send('emails.password-change-verification', [
                'user' => $user,
                'token' => $token,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Verifikasi Perubahan Password');
            });

            return redirect()->route('login')->with('success', 'Link verifikasi perubahan password telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi.']);
        }
    }

    /**
     * -------------------------------------------------------------------------
     * Verifikasi Perubahan Password (Flow Alternatif)
     * -------------------------------------------------------------------------
     * Method ini memproses verifikasi dari link email dan mengaktifkan
     * password baru. Dipanggil saat user klik link di email.
     * 
     * Alur proses:
     * 1. Cari token di cache (iterasi semua user)
     * 2. Validasi token belum expired
     * 3. Update password user
     * 4. Hapus data dari cache
     * 
     * Catatan: Iterasi semua user kurang efisien untuk skala besar.
     * Pertimbangkan menggunakan database table seperti PasswordChangeToken.
     * 
     * @param Request $request Data request
     * @param string $token Token verifikasi dari URL
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status
     */
    public function verifyPasswordChange(Request $request, $token)
    {
        // -----------------------------------------------------------------
        // LANGKAH 1: Cari Token di Cache
        // -----------------------------------------------------------------
        // Iterasi semua user untuk menemukan cache yang cocok
        // (Ini kurang efisien, tapi simple untuk skala kecil)
        $users = UserAccount::all();
        $user = null;
        $cacheKey = '';

        foreach ($users as $u) {
            $cacheKey = 'password_change_' . $u->id;
            $cachedData = cache($cacheKey);

            // Jika token cocok, user ditemukan
            if ($cachedData && $cachedData['token'] === $token) {
                $user = $u;
                break;
            }
        }

        // Token tidak ditemukan = link tidak valid
        if (!$user || !$cachedData) {
            return redirect()->route('password.request')->withErrors(['token' => 'Link verifikasi tidak valid atau telah kadaluarsa.']);
        }

        // -----------------------------------------------------------------
        // LANGKAH 2: Cek Expired
        // -----------------------------------------------------------------
        // Pastikan token belum melewati waktu expired
        if (Carbon::now()->greaterThan($cachedData['expires_at'])) {
            cache()->forget($cacheKey);
            return redirect()->route('password.request')->withErrors(['token' => 'Waktu verifikasi habis, silakan isi ulang form reset password.']);
        }

        // -----------------------------------------------------------------
        // LANGKAH 3: Update Password
        // -----------------------------------------------------------------
        // Password baru sudah di-hash saat disimpan ke cache
        $user->password = $cachedData['new_password'];
        $user->save();

        // -----------------------------------------------------------------
        // LANGKAH 4: Hapus Cache
        // -----------------------------------------------------------------
        // Token hanya boleh dipakai sekali
        cache()->forget($cacheKey);

        return redirect()->route('profile.show')->with('success', 'Password berhasil diperbarui.');
    }
}
