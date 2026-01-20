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
     * -------------------------------------------------------------------------
     * Action: Login
     * -------------------------------------------------------------------------
     */
    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@student\.unsika\.ac\.id$/'],
            'password' => ['required', 'string'],
        ]);

        // Rate limiting
        $throttleKey = strtolower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Attempt login
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::guard('user_accounts')->attempt($credentials, $this->remember)) {
            RateLimiter::clear($throttleKey);

            /** @var UserAccount $user */
            $user = Auth::guard('user_accounts')->user();

            // Check email verification
            if (!$user->hasVerifiedEmail()) {
                session()->flash('status', 'Silakan verifikasi email Anda terlebih dahulu.');
                $this->redirect(route('verification.notice'));
                return;
            }

            session()->regenerate();
            $this->redirect(route('profile.show'));
            return;
        }

        RateLimiter::hit($throttleKey);

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
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
