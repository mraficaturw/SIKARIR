<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ============================================================================
 * FORM REQUEST: REGISTRASI
 * ============================================================================
 * Class ini menangani validasi data registrasi pengguna baru.
 * Digunakan oleh RegisterController untuk memvalidasi input.
 * 
 * Keuntungan menggunakan Form Request:
 * - Memisahkan logic validasi dari controller (clean code)
 * - Bisa digunakan ulang di tempat lain
 * - Built-in support untuk error handling dan redirect
 * 
 * Field yang divalidasi:
 * - name: Wajib, string, maksimal 255 karakter
 * - email: Wajib, format email valid, unik di database
 * - password: Wajib, dikonfirmasi, memenuhi standar keamanan
 * ============================================================================
 */
class RegisterRequest extends FormRequest
{
    /**
     * -------------------------------------------------------------------------
     * Otorisasi Request
     * -------------------------------------------------------------------------
     * Menentukan apakah user diizinkan melakukan request ini.
     * 
     * Untuk registrasi, semua orang boleh (return true).
     * Bisa diubah jika ingin membatasi registrasi, contoh:
     * - return config('app.registration_open');
     * - return !Auth::check(); // Hanya untuk guest
     * 
     * @return bool True jika diizinkan, false jika tidak
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * -------------------------------------------------------------------------
     * Aturan Validasi
     * -------------------------------------------------------------------------
     * Mendefinisikan aturan validasi untuk setiap field.
     * Laravel akan otomatis menolak request jika tidak valid
     * dan mengembalikan error ke form.
     * 
     * Penjelasan rules:
     * 
     * NAME:
     * - required: Wajib diisi
     * - string: Harus berupa string
     * - max:255: Maksimal 255 karakter
     * 
     * EMAIL:
     * - required: Wajib diisi
     * - string: Harus berupa string
     * - email: Harus format email valid (xxx@xxx.xxx)
     * - max:255: Maksimal 255 karakter
     * - unique:user_accounts: Tidak boleh sudah ada di tabel user_accounts
     * 
     * PASSWORD (Standar keamanan tinggi):
     * - required: Wajib diisi
     * - confirmed: Harus ada field password_confirmation dengan nilai sama
     * - min(8): Minimal 8 karakter
     * - mixedCase(): Harus ada huruf besar DAN huruf kecil
     * - numbers(): Harus ada minimal 1 angka
     * - symbols(): Harus ada minimal 1 simbol (!@#$%^&*dll)
     * - uncompromised(): Tidak boleh ada di database password breach
     *   (dicek melalui Have I Been Pwned API)
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Validasi nama
            'name'     => ['required', 'string', 'max:255'],

            // Validasi email - harus unik di tabel user_accounts
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:user_accounts'],

            // Validasi password dengan standar keamanan tinggi
            'password' => [
                'required',
                'confirmed', // Harus match dengan password_confirmation

                // Gunakan Password Rule class untuk validasi kompleks
                \Illuminate\Validation\Rules\Password::min(8)  // Minimal 8 karakter
                    ->mixedCase()    // Harus ada huruf besar dan kecil (Aa)
                    ->numbers()      // Harus ada angka (123)
                    ->symbols()      // Harus ada simbol (!@#)
                    ->uncompromised(), // Tidak ada di database breach
            ],
        ];
    }
}
