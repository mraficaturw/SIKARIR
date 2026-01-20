<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\UserAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * ============================================================================
 * REGISTER CONTROLLER
 * ============================================================================
 * Controller ini menangani proses pendaftaran pengguna baru di SIKARIR.
 * 
 * Fitur utama:
 * - Menampilkan form registrasi
 * - Memvalidasi data pendaftaran (nama, email, password)
 * - Membuat akun pengguna baru
 * - Mengirim email verifikasi otomatis
 * 
 * Validasi password menggunakan aturan ketat:
 * - Minimal 8 karakter
 * - Harus ada huruf besar dan kecil
 * - Harus ada angka
 * - Harus ada simbol
 * ============================================================================
 */
class RegisterController extends Controller
{
    /**
     * -------------------------------------------------------------------------
     * Menampilkan Form Registrasi
     * -------------------------------------------------------------------------
     * Method ini dipanggil ketika pengguna mengakses halaman /register.
     * Menampilkan form untuk pengguna baru mendaftar.
     * 
     * @return \Illuminate\View\View Halaman form registrasi
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * -------------------------------------------------------------------------
     * Memproses Registrasi Pengguna Baru
     * -------------------------------------------------------------------------
     * Method ini memproses data registrasi yang dikirim dari form.
     * 
     * Alur proses:
     * 1. Validasi input menggunakan RegisterRequest (nama, email, password)
     * 2. Buat akun pengguna baru dengan password ter-hash
     * 3. Login otomatis pengguna ke guard 'user_accounts'
     * 4. Kirim email verifikasi melalui event Registered
     * 5. Redirect ke halaman verifikasi email
     * 
     * Catatan keamanan:
     * - Password di-hash menggunakan Hash::make() sebelum disimpan
     * - Email harus unik (tidak boleh duplikat)
     * - Password harus memenuhi standar keamanan (lihat RegisterRequest)
     * 
     * @param RegisterRequest $request Data request yang sudah divalidasi
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman verifikasi
     */
    public function register(RegisterRequest $request)
    {
        // -----------------------------------------------------------------
        // LANGKAH 1: Ambil Data yang Sudah Divalidasi
        // -----------------------------------------------------------------
        // RegisterRequest sudah memvalidasi:
        // - name: required, string, max 255
        // - email: required, email, unique di tabel user_accounts
        // - password: required, confirmed, min 8, huruf besar/kecil, angka, simbol
        $validated = $request->validated();

        // -----------------------------------------------------------------
        // LANGKAH 2: Buat Akun Pengguna Baru
        // -----------------------------------------------------------------
        // Simpan ke database dengan password yang sudah di-hash.
        // Hash::make() mengenkripsi password sehingga tidak tersimpan plain text.
        $user = UserAccount::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // -----------------------------------------------------------------
        // LANGKAH 3: Login Otomatis
        // -----------------------------------------------------------------
        // Setelah registrasi, pengguna langsung login ke guard 'user_accounts'.
        // Ini diperlukan agar email verifikasi bisa dikirim dengan benar.
        Auth::guard('user_accounts')->login($user);

        // -----------------------------------------------------------------
        // LANGKAH 4: Kirim Email Verifikasi
        // -----------------------------------------------------------------
        // Event Registered akan memicu pengiriman email verifikasi.
        // Email dikirim menggunakan CustomVerifyEmail notification.
        // Pengguna harus klik link di email untuk verifikasi.
        event(new Registered($user));

        // -----------------------------------------------------------------
        // LANGKAH 5: Redirect ke Halaman Verifikasi
        // -----------------------------------------------------------------
        // Pengguna diarahkan ke halaman yang menginformasikan
        // bahwa email verifikasi sudah dikirim.
        return redirect()->route('verification.notice')
            ->with('message', 'Verifikasi email telah dikirim. Silakan cek email Anda.');
    }
}
