<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordChangeToken;
use App\Models\UserAccount;
use App\Models\Internjob;
use App\Notifications\VerifyPasswordChange;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * ============================================================================
 * PROFILE CONTROLLER
 * ============================================================================
 * Controller ini menangani semua operasi terkait profil pengguna.
 * 
 * Fitur utama:
 * - Menampilkan profil pengguna dengan daftar favorit dan lamaran
 * - Edit profil (nama dan avatar)
 * - Ganti password dengan verifikasi email
 * - Upload avatar ke Supabase Storage (format WebP)
 * 
 * Semua method membutuhkan autentikasi (middleware auth:user_accounts).
 * Sebagian besar method juga membutuhkan email terverifikasi.
 * ============================================================================
 */
class ProfileController extends Controller
{
    /**
     * -------------------------------------------------------------------------
     * Menampilkan Halaman Profil
     * -------------------------------------------------------------------------
     * Method ini menampilkan halaman profil pengguna beserta:
     * - Informasi dasar pengguna (nama, email, avatar)
     * - Daftar lowongan yang difavoritkan
     * - Daftar lowongan yang sudah dilamar
     * 
     * @return \Illuminate\View\View Halaman profil dengan data lengkap
     */
    public function show()
    {
        /** @var UserAccount $user */
        // Ambil data pengguna yang sedang login
        $user = Auth::guard('user_accounts')->user();

        // -----------------------------------------------------------------
        // Ambil Relasi Favorit dan Lamaran
        // -----------------------------------------------------------------
        // Gunakan null coalescing (??) untuk mencegah error jika relasi null.
        // collect() mengembalikan empty collection jika tidak ada data.
        $favorites = $user->favorites ?? collect();
        $appliedJobs = $user->appliedJobs ?? collect();

        return view('profile.profile', compact('user', 'favorites', 'appliedJobs'));
    }

    /**
     * -------------------------------------------------------------------------
     * Menampilkan Form Edit Profil
     * -------------------------------------------------------------------------
     * Method ini menampilkan form untuk mengedit profil pengguna.
     * Form berisi field untuk nama dan upload avatar baru.
     * 
     * @return \Illuminate\View\View Halaman form edit profil
     */
    public function editForm()
    {
        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        return view('profile.edit-profile', compact('user'));
    }

    /**
     * -------------------------------------------------------------------------
     * Memproses Update Profil
     * -------------------------------------------------------------------------
     * Method ini memproses perubahan profil dari form edit.
     * 
     * Fitur:
     * - Update nama pengguna
     * - Upload avatar baru (opsional)
     * - Konversi gambar ke format WebP untuk efisiensi
     * - Hapus avatar lama dari storage jika ada yang baru
     * 
     * Catatan teknis:
     * - Avatar disimpan di Supabase Storage (disk: supabase-avatar)
     * - Gambar dikonversi ke WebP dengan kualitas 80%
     * - Maksimal ukuran file: 5MB
     * 
     * @param Request $request Data request berisi nama dan file avatar
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman profil
     */
    public function updateProfile(Request $request)
    {
        // -----------------------------------------------------------------
        // LANGKAH 1: Validasi Input
        // -----------------------------------------------------------------
        // - name: wajib diisi, string, maksimal 255 karakter
        // - avatar: opsional, harus gambar, maksimal 5MB (5120 KB)
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:5120', // Max 5MB
        ]);

        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        // Update nama pengguna
        $user->name = $request->name;

        // -----------------------------------------------------------------
        // LANGKAH 2: Handle Upload Avatar (jika ada)
        // -----------------------------------------------------------------
        if ($request->hasFile('avatar')) {

            // Hapus avatar lama dari Supabase Storage jika ada
            // Ini mencegah penumpukan file yang tidak terpakai
            if ($user->avatar) {
                Storage::disk('supabase-avatar')->delete($user->avatar);
            }

            // Upload avatar baru menggunakan ImageService
            // Service ini otomatis konversi gambar ke format WebP
            // Format WebP lebih kecil ukurannya tanpa mengurangi kualitas
            $path = ImageService::convertAndUpload(
                $request->file('avatar'),  // File yang diupload
                'supabase-avatar',          // Nama disk storage
                'avatars',                  // Folder tujuan
                80                          // Kualitas gambar (80%)
            );

            // Simpan path file baru ke database
            $user->avatar = $path;
        }

        // -----------------------------------------------------------------
        // LANGKAH 3: Simpan Perubahan
        // -----------------------------------------------------------------
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * -------------------------------------------------------------------------
     * Menampilkan Form Ganti Password
     * -------------------------------------------------------------------------
     * Method ini menampilkan form untuk mengubah password.
     * Form berisi field untuk password lama, password baru, dan konfirmasi.
     * 
     * @return \Illuminate\View\View Halaman form ganti password
     */
    public function changePasswordForm()
    {
        return view('profile.change-password');
    }

    /**
     * -------------------------------------------------------------------------
     * Memproses Perubahan Password
     * -------------------------------------------------------------------------
     * Method ini memproses permintaan ganti password dengan verifikasi email.
     * 
     * Alur proses (2 langkah keamanan):
     * 1. User submit password baru → dikirim email verifikasi
     * 2. User klik link di email → password benar-benar diganti
     * 
     * Fitur keamanan:
     * - Rate limiting: maksimal 3 percobaan per 5 menit
     * - Validasi password lama harus benar
     * - Password baru harus berbeda dari yang lama
     * - Password baru harus memenuhi standar keamanan
     * - Verifikasi via email sebelum perubahan aktif
     * - Token verifikasi expires dalam 60 menit
     * 
     * @param Request $request Data request berisi password lama dan baru
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status
     */
    public function changePassword(Request $request)
    {
        // -----------------------------------------------------------------
        // LANGKAH 1: Rate Limiting
        // -----------------------------------------------------------------
        // Batasi 3 percobaan per 5 menit untuk mencegah brute force
        // pada fitur perubahan password.
        $key = 'password-change:' . auth()->id();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            return back()->withErrors(['error' => "Terlalu banyak percobaan. Silakan tunggu {$seconds} detik."]);
        }
        // Catat percobaan ini (decay: 300 detik = 5 menit)
        \Illuminate\Support\Facades\RateLimiter::hit($key, 300);

        // -----------------------------------------------------------------
        // LANGKAH 2: Validasi Input
        // -----------------------------------------------------------------
        // Password baru harus memenuhi standar keamanan:
        // - Minimal 8 karakter
        // - Harus ada huruf besar dan kecil (mixedCase)
        // - Harus ada angka (numbers)
        // - Harus ada simbol (symbols)
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'confirmed', // Harus sama dengan password_confirmation
                \Illuminate\Validation\Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        // -----------------------------------------------------------------
        // LANGKAH 3: Validasi Password Lama
        // -----------------------------------------------------------------
        // Pastikan pengguna memasukkan password lama yang benar.
        // Ini mencegah orang lain mengganti password jika device tidak aman.
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // -----------------------------------------------------------------
        // LANGKAH 4: Validasi Password Baru Berbeda
        // -----------------------------------------------------------------
        // Password baru tidak boleh sama dengan password lama.
        // Ini memastikan pengguna benar-benar mengubah password.
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama.']);
        }

        // Hapus catatan rate limiting setelah validasi berhasil
        \Illuminate\Support\Facades\RateLimiter::clear($key);

        // -----------------------------------------------------------------
        // LANGKAH 5: Buat Token Verifikasi
        // -----------------------------------------------------------------
        // Hapus token lama jika ada (satu user hanya boleh punya 1 token aktif)
        PasswordChangeToken::where('user_id', $user->id)->delete();

        // Generate token baru secara acak (64 karakter)
        $token = Str::random(64);

        // Simpan token dengan password baru yang sudah di-hash
        // Token expires dalam 60 menit
        PasswordChangeToken::create([
            'user_id' => $user->id,
            'new_password' => Hash::make($request->password),
            'token' => $token,
            'expires_at' => now()->addMinutes(60),
        ]);

        // -----------------------------------------------------------------
        // LANGKAH 6: Kirim Email Verifikasi
        // -----------------------------------------------------------------
        // Pengguna akan menerima email dengan link verifikasi.
        // Password baru akan aktif setelah klik link tersebut.
        $user->notify(new VerifyPasswordChange($token));

        return back()->with('status', 'Email verifikasi telah dikirim. Silakan cek email Anda untuk mengkonfirmasi perubahan password.');
    }

    /**
     * -------------------------------------------------------------------------
     * Verifikasi Perubahan Password
     * -------------------------------------------------------------------------
     * Method ini memproses verifikasi dari link email.
     * Ini adalah langkah kedua dari proses ganti password.
     * 
     * Alur proses:
     * 1. Cari token di database
     * 2. Validasi token belum expired (max 60 menit)
     * 3. Update password pengguna dengan password baru
     * 4. Hapus token dari database
     * 
     * @param string $token Token verifikasi dari URL
     * @return \Illuminate\Http\RedirectResponse Redirect ke profil atau error
     */
    public function verifyPasswordChange(string $token)
    {
        // -----------------------------------------------------------------
        // LANGKAH 1: Cari Token
        // -----------------------------------------------------------------
        // Cari token di database berdasarkan string token
        $passwordToken = PasswordChangeToken::where('token', $token)->first();

        // Token tidak ditemukan = link tidak valid
        if (!$passwordToken) {
            return redirect()->route('profile.change-password')
                ->withErrors(['token' => 'Link verifikasi tidak valid.']);
        }

        // -----------------------------------------------------------------
        // LANGKAH 2: Cek Expired
        // -----------------------------------------------------------------
        // Token hanya berlaku 60 menit setelah dibuat
        if ($passwordToken->isExpired()) {
            // Hapus token yang sudah expired
            $passwordToken->delete();
            return redirect()->route('profile.change-password')
                ->withErrors(['token' => 'Link verifikasi sudah kadaluarsa. Silakan minta perubahan password baru.']);
        }

        // -----------------------------------------------------------------
        // LANGKAH 3: Update Password
        // -----------------------------------------------------------------
        // Ambil pengguna dari relasi token dan update password-nya
        $user = $passwordToken->user;
        $user->update([
            'password' => $passwordToken->new_password, // Sudah di-hash saat disimpan
        ]);

        // -----------------------------------------------------------------
        // LANGKAH 4: Hapus Token
        // -----------------------------------------------------------------
        // Token hanya bisa dipakai sekali, hapus setelah berhasil
        $passwordToken->delete();

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diubah!');
    }
}
