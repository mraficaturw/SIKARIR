<?php

namespace App\Livewire;

use App\Models\Vacancy;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * ============================================================================
 * LIVEWIRE COMPONENT: APPLY BUTTON
 * ============================================================================
 * Komponen tombol apply/lamar yang reactive tanpa reload halaman.
 * Digunakan di card lowongan dan halaman detail lowongan.
 * 
 * Perbedaan dengan FavoriteButton:
 * - Menyimpan timestamp kapan user menandai "sudah apply"
 * - Bisa menyimpan URL external untuk apply
 * 
 * Catatan: Tombol ini hanya untuk tracking internal.
 * User tetap perlu apply melalui link external yang disediakan perusahaan.
 * 
 * View: resources/views/livewire/apply-button.blade.php
 * ============================================================================
 */
class ApplyButton extends Component
{
    /**
     * -------------------------------------------------------------------------
     * Properties (State Komponen)
     * -------------------------------------------------------------------------
     */

    /** @var int ID lowongan yang akan di-toggle statusnya */
    public int $jobId;

    /** @var bool Status apakah user sudah menandai "sudah apply" */
    public bool $isApplied = false;

    /** @var string|null URL external untuk apply (opsional) */
    public ?string $applyUrl = null;

    /**
     * -------------------------------------------------------------------------
     * Mount (Inisialisasi Komponen)
     * -------------------------------------------------------------------------
     * Method ini dipanggil sekali saat komponen pertama kali dibuat.
     * Menginisialisasi state tombol berdasarkan data di database.
     * 
     * @param int $jobId ID lowongan untuk tombol ini
     * @param string|null $applyUrl URL untuk apply (dari lowongan)
     * @return void
     */
    public function mount(int $jobId, ?string $applyUrl = null)
    {
        $this->jobId = $jobId;
        $this->applyUrl = $applyUrl;

        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        // Cek status apply jika user login dan email sudah verified
        if ($user && $user->hasVerifiedEmail()) {
            // Query ke pivot table untuk cek apakah sudah ada record
            $this->isApplied = $user->appliedJobs()->where('vacancy_id', $this->jobId)->exists();
        }
    }

    /**
     * -------------------------------------------------------------------------
     * Action: Toggle Status Apply
     * -------------------------------------------------------------------------
     * Method ini dipanggil saat user mengklik tombol apply.
     * Melakukan toggle (tandai/batalkan) status sudah apply di database.
     * 
     * Validasi:
     * 1. User harus login → redirect ke login jika tidak
     * 2. Email harus verified → redirect ke verification notice jika tidak
     * 
     * Saat menandai "sudah apply":
     * - Timestamp 'applied_at' dicatat di pivot table
     * - Berguna untuk tracking kapan user melamar
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
            return $this->redirect(route('login'));
        }

        // -----------------------------------------------------------------
        // Validasi: Email Harus Sudah Diverifikasi
        // -----------------------------------------------------------------
        if (!$user->hasVerifiedEmail()) {
            return $this->redirect(route('verification.notice'));
        }

        // -----------------------------------------------------------------
        // Toggle Status Apply
        // -----------------------------------------------------------------
        if ($user->appliedJobs()->where('vacancy_id', $this->jobId)->exists()) {
            // Sudah ditandai apply → hapus tanda (detach)
            $user->appliedJobs()->detach($this->jobId);
            $this->isApplied = false;
        } else {
            // Belum ditandai → tandai sebagai sudah apply (attach)
            // Simpan juga timestamp kapan user menandai ini
            $user->appliedJobs()->attach($this->jobId, ['applied_at' => now()]);
            $this->isApplied = true;
        }

        // Tampilan tombol akan otomatis update karena $isApplied berubah
    }

    /**
     * -------------------------------------------------------------------------
     * Render Komponen
     * -------------------------------------------------------------------------
     * Method ini me-render view tombol apply.
     * 
     * View akan menampilkan:
     * - Icon/warna berbeda berdasarkan $isApplied
     * - Link ke $applyUrl jika tersedia
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.apply-button');
    }
}
