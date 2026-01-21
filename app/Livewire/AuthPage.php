<?php

namespace App\Livewire;

use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/**
 * ============================================================================
 * LIVEWIRE COMPONENT: AUTH PAGE
 * ============================================================================
 * Komponen Livewire untuk halaman login/register SPA.
 * Memungkinkan transisi smooth antara form login dan register tanpa reload.
 * 
 * Fitur:
 * - Single Page Auth (switch tanpa reload)
 * - Animasi transisi sliding panel
 * - Form validation real-time
 * - Rate limiting untuk keamanan
 * ============================================================================
 */
class AuthPage extends Component
{
    /**
     * -------------------------------------------------------------------------
     * Properties (State Komponen)
     * -------------------------------------------------------------------------
     */

    /** @var string Mode saat ini: 'login' atau 'register' */
    public string $mode = 'login';

    /** @var string Email input */
    public string $email = '';

    /** @var string Password input */
    public string $password = '';

    /** @var string Nama lengkap (untuk register) */
    public string $name = '';

    /** @var string Konfirmasi password (untuk register) */
    public string $password_confirmation = '';

    /** @var bool Remember me checkbox */
    public bool $remember = false;

    /**
     * -------------------------------------------------------------------------
     * Validation Rules
     * -------------------------------------------------------------------------
     */
    protected function rules(): array
    {
        $rules = [
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@student\.unsika\.ac\.id$/'],
        ];

        if ($this->mode === 'login') {
            $rules['password'] = ['required', 'string'];
        } else {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            $rules['password_confirmation'] = ['required'];
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'email.regex' => 'Gunakan email dengan domain @student.unsika.ac.id',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'name.required' => 'Nama lengkap wajib diisi',
        ];
    }

    /**
     * -------------------------------------------------------------------------
     * Action: Switch Mode
     * -------------------------------------------------------------------------
     * Beralih antara mode login dan register dengan animasi smooth.
     */
    public function switchMode(string $newMode): void
    {
        $this->reset(['email', 'password', 'name', 'password_confirmation', 'remember']);
        $this->resetValidation();
        $this->mode = $newMode;
    }

    /**
     * Maksimal percobaan password salah sebelum akun dikunci (reset verifikasi email)
     */
    protected int $maxPasswordAttempts = 5;

    /**
     * -------------------------------------------------------------------------
     * Action: Login
     * -------------------------------------------------------------------------
     * Alur login yang diperbarui:
     * 1. Cek apakah email terdaftar di database
     * 2. Jika email tidak ditemukan, tampilkan "email belum terdaftar"
     * 3. Jika email ditemukan tapi password salah, tampilkan sisa percobaan
     * 4. Setelah 5x password salah, reset verifikasi email dan harus verifikasi ulang
     */
    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@student\.unsika\.ac\.id$/'],
            'password' => ['required', 'string'],
        ]);

        // -----------------------------------------------------------------
        // LANGKAH 1: Cek apakah email terdaftar
        // -----------------------------------------------------------------
        $user = UserAccount::where('email', strtolower($this->email))->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email belum terdaftar.',
            ]);
        }

        // -----------------------------------------------------------------
        // LANGKAH 2: Rate limiting per-user (berbasis email saja)
        // -----------------------------------------------------------------
        // Key berbasis email untuk tracking percobaan password per akun
        $passwordThrottleKey = 'password_attempts:' . strtolower($this->email);

        // Cek apakah akun sudah terkunci karena terlalu banyak percobaan
        if (RateLimiter::tooManyAttempts($passwordThrottleKey, $this->maxPasswordAttempts)) {
            $seconds = RateLimiter::availableIn($passwordThrottleKey);
            throw ValidationException::withMessages([
                'password' => "Akun terkunci sementara. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        // -----------------------------------------------------------------
        // LANGKAH 3: Cek password
        // -----------------------------------------------------------------
        if (!Hash::check($this->password, $user->password)) {
            // Catat percobaan gagal
            RateLimiter::hit($passwordThrottleKey, 300); // Decay 5 menit

            // Hitung sisa percobaan
            $currentAttempts = RateLimiter::attempts($passwordThrottleKey);
            $remainingAttempts = $this->maxPasswordAttempts - $currentAttempts;

            // -----------------------------------------------------------------
            // LANGKAH 4: Cek apakah sudah 5x gagal
            // -----------------------------------------------------------------
            if ($remainingAttempts <= 0) {
                // Reset verifikasi email - user harus verifikasi ulang
                $user->email_verified_at = null;
                $user->save();

                // Kirim ulang email verifikasi
                $user->sendEmailVerificationNotification();

                // Clear rate limiter setelah reset
                RateLimiter::clear($passwordThrottleKey);

                throw ValidationException::withMessages([
                    'password' => 'Anda telah gagal login 5 kali. Untuk keamanan akun, verifikasi email diperlukan. Silakan cek email Anda untuk link verifikasi baru.',
                ]);
            }

            // Tampilkan pesan dengan sisa percobaan
            $attemptWord = $remainingAttempts === 1 ? 'percobaan' : 'percobaan';
            throw ValidationException::withMessages([
                'password' => "Password salah. Sisa {$remainingAttempts} {$attemptWord} lagi sebelum akun terkunci.",
            ]);
        }

        // -----------------------------------------------------------------
        // LANGKAH 5: Password benar - Clear rate limiter
        // -----------------------------------------------------------------
        RateLimiter::clear($passwordThrottleKey);

        // Login user
        Auth::guard('user_accounts')->login($user, $this->remember);

        // -----------------------------------------------------------------
        // LANGKAH 6: Cek verifikasi email
        // -----------------------------------------------------------------
        if (!$user->hasVerifiedEmail()) {
            session()->flash('status', 'Silakan verifikasi email Anda terlebih dahulu.');
            $this->redirect(route('verification.notice'));
            return;
        }

        session()->regenerate();
        $this->redirect(route('profile.show'));
    }

    /**
     * -------------------------------------------------------------------------
     * Action: Register
     * -------------------------------------------------------------------------
     */
    public function register(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@student\.unsika\.ac\.id$/', 'unique:user_accounts,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = UserAccount::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Send verification email
        $user->sendEmailVerificationNotification();

        session()->flash('success', 'Akun berhasil dibuat! Silakan cek email untuk verifikasi.');

        // Switch to login mode
        $this->reset(['name', 'password', 'password_confirmation']);
        $this->mode = 'login';
    }

    /**
     * -------------------------------------------------------------------------
     * Mount
     * -------------------------------------------------------------------------
     */
    public function mount(string $initialMode = 'login'): void
    {
        $this->mode = $initialMode;
    }

    /**
     * -------------------------------------------------------------------------
     * Render
     * -------------------------------------------------------------------------
     */
    public function render()
    {
        return view('livewire.auth-page');
    }
}
