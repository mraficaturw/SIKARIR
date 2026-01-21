<?php

namespace App\Livewire;

use App\Models\Vacancy;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * ============================================================================
 * LIVEWIRE COMPONENT: FAVORITE BUTTON
 * ============================================================================
 * Komponen tombol favorit yang reactive tanpa reload halaman.
 * Digunakan di card lowongan dan halaman detail lowongan.
 * 
 * Fitur:
 * - Toggle favorit dengan satu klik
 * - State otomatis update tanpa reload
 * - Redirect ke login jika belum autentikasi
 * - Redirect ke verifikasi jika email belum verified
 * 
 * View: resources/views/livewire/favorite-button.blade.php
 * ============================================================================
 */
class FavoriteButton extends Component
{
    /**
     * -------------------------------------------------------------------------
     * Properties (State Komponen)
     * -------------------------------------------------------------------------
     */

    /** @var int ID lowongan yang akan di-toggle favoritnya */
    public int $jobId;

    /** @var bool Status apakah lowongan sudah difavoritkan */
    public bool $isFavorite = false;

    /**
     * -------------------------------------------------------------------------
     * Mount (Inisialisasi Komponen)
     * -------------------------------------------------------------------------
     * Method ini dipanggil sekali saat komponen pertama kali dibuat.
     * Digunakan untuk menginisialisasi state awal tombol.
     * 
     * @param int $jobId ID lowongan untuk tombol ini
     * @return void
     */
    public function mount(int $jobId)
    {
        $this->jobId = $jobId;

        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        // Cek status favorit jika user login dan email sudah verified
        // Jika tidak, tombol akan dalam state "belum difavoritkan"
        if ($user && $user->hasVerifiedEmail()) {
            // Query ke pivot table untuk cek apakah sudah ada
            $this->isFavorite = $user->favorites()->where('vacancy_id', $this->jobId)->exists();
        }
    }

    /**
     * -------------------------------------------------------------------------
     * Action: Toggle Status Favorit
     * -------------------------------------------------------------------------
     * Method ini dipanggil saat user mengklik tombol favorit.
     * Melakukan toggle (tambah/hapus) status favorit di database.
     * 
     * Validasi:
     * 1. User harus login → redirect ke login jika tidak
     * 2. Email harus verified → redirect ke verification notice jika tidak
     * 
     * Setelah toggle, property $isFavorite diupdate sehingga
     * tampilan tombol berubah secara reactive.
     * 
     * @return \Livewire\Features\SupportRedirects\Redirector|void
     */
    public function toggle()
    {
        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        // -----------------------------------------------------------------
        // Validasi: User Harus Login
        // -----------------------------------------------------------------
        if (!$user) {
            // Redirect ke halaman login jika belum login
            return $this->redirect(route('login'));
        }

        // -----------------------------------------------------------------
        // Validasi: Email Harus Sudah Diverifikasi
        // -----------------------------------------------------------------
        if (!$user->hasVerifiedEmail()) {
            // Redirect ke halaman verifikasi jika email belum verified
            return $this->redirect(route('verification.notice'));
        }

        // Pastikan lowongan ada di database
        $job = Vacancy::findOrFail($this->jobId);

        // -----------------------------------------------------------------
        // Toggle Status Favorit
        // -----------------------------------------------------------------
        if ($user->favorites()->where('vacancy_id', $this->jobId)->exists()) {
            // Sudah difavoritkan → hapus dari daftar favorit
            $user->favorites()->detach($this->jobId);
            $this->isFavorite = false;
        } else {
            // Belum difavoritkan → tambahkan ke daftar favorit
            $user->favorites()->attach($this->jobId);
            $this->isFavorite = true;
        }

        // Tampilan tombol akan otomatis update karena $isFavorite berubah
    }

    /**
     * -------------------------------------------------------------------------
     * Render Komponen
     * -------------------------------------------------------------------------
     * Method ini me-render view tombol favorit.
     * View akan menampilkan icon berbeda berdasarkan $isFavorite.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.favorite-button');
    }
}
