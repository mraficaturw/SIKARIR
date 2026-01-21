<?php

namespace App\Livewire;

use App\Models\Vacancy;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * ============================================================================
 * LIVEWIRE COMPONENT: JOB SEARCH
 * ============================================================================
 * Komponen Livewire untuk pencarian lowongan secara real-time.
 * Digunakan di halaman daftar lowongan (/jobs).
 * 
 * Fitur utama:
 * - Pencarian real-time (tanpa reload halaman)
 * - Filter berdasarkan kategori fakultas
 * - Pagination yang terintegrasi dengan pencarian
 * - Sync state dengan URL query string
 * 
 * View: resources/views/livewire/job-search.blade.php
 * ============================================================================
 */
class JobSearch extends Component
{
    /**
     * Trait untuk pagination di Livewire.
     * Memungkinkan penggunaan $jobs->links() di view.
     */
    use WithPagination;

    /**
     * -------------------------------------------------------------------------
     * Properties (State Komponen)
     * -------------------------------------------------------------------------
     * Properties ini menyimpan state pencarian saat ini.
     * Perubahan pada property akan otomatis me-render ulang komponen.
     */

    /** @var string Kata kunci pencarian yang diketik user */
    public string $search = '';

    /** @var string Kategori fakultas yang dipilih untuk filter */
    public string $category = '';

    /**
     * -------------------------------------------------------------------------
     * Query String Binding
     * -------------------------------------------------------------------------
     * Konfigurasi ini menyinkronkan property dengan URL query string.
     * 
     * Contoh: /jobs?search=engineer&category=Fakultas%20Teknik
     * 
     * 'except' => '' : Tidak tampil di URL jika nilai kosong
     * Ini menjaga URL tetap bersih saat tidak ada filter aktif.
     */
    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
    ];

    /**
     * -------------------------------------------------------------------------
     * Hook: Search Sedang Diupdate
     * -------------------------------------------------------------------------
     * Method ini dipanggil sebelum property $search berubah.
     * 
     * Kita reset pagination ke halaman 1 saat pencarian baru,
     * karena hasil pencarian baru mungkin lebih sedikit halamannya.
     * 
     * @return void
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * -------------------------------------------------------------------------
     * Hook: Category Sedang Diupdate
     * -------------------------------------------------------------------------
     * Method ini dipanggil sebelum property $category berubah.
     * Reset pagination saat filter kategori berubah.
     * 
     * @return void
     */
    public function updatingCategory()
    {
        $this->resetPage();
    }

    /**
     * -------------------------------------------------------------------------
     * Action: Eksekusi Pencarian
     * -------------------------------------------------------------------------
     * Method ini bisa dipanggil dari view untuk trigger pencarian.
     * Berguna untuk form submit atau tombol search.
     * 
     * @return void
     */
    public function performSearch()
    {
        $this->resetPage();
    }

    /**
     * -------------------------------------------------------------------------
     * Computed Property: Daftar Fakultas
     * -------------------------------------------------------------------------
     * Mengembalikan daftar fakultas untuk dropdown filter.
     * 
     * Menggunakan config terpusat untuk menghindari duplikasi.
     * Diakses sebagai $this->faculties di view.
     * 
     * @return array Daftar fakultas [key => label]
     */
    public function getFacultiesProperty(): array
    {
        return collect(config('sikarir.faculties'))
            ->mapWithKeys(fn($data, $key) => [$key => $data['label']])
            ->toArray();
    }

    /**
     * -------------------------------------------------------------------------
     * Render Komponen
     * -------------------------------------------------------------------------
     * Method ini dipanggil setiap kali komponen perlu di-render.
     * Di sinilah query database dan logic presentasi terjadi.
     * 
     * Alur:
     * 1. Bangun query dasar dengan eager loading company
     * 2. Terapkan filter pencarian jika ada
     * 3. Terapkan filter kategori jika ada
     * 4. Paginasi hasil (10 per halaman)
     * 5. Ambil data user untuk status favorit/apply
     * 6. Return view dengan data
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // -----------------------------------------------------------------
        // Bangun Query Lowongan
        // -----------------------------------------------------------------
        // Eager load relasi 'company' untuk menghindari N+1 query
        // -----------------------------------------------------------------
        // Query Lowongan dengan Filter (menggunakan scopeSearch)
        // -----------------------------------------------------------------
        $jobs = Vacancy::with('company')
            ->search($this->search, $this->category)
            ->orderBy('created_at', 'desc')
            ->paginate(config('sikarir.pagination.jobs_per_page', 10));

        // -----------------------------------------------------------------
        // Ambil Data User (untuk Status Favorit/Apply)
        // -----------------------------------------------------------------
        $user = Auth::guard('user_accounts')->user();

        // Load relasi favorit dan applied jobs jika user login
        // Ini digunakan untuk menampilkan icon yang sesuai di tombol
        if ($user instanceof UserAccount) {
            $user->load(['favorites', 'appliedJobs']);
        }

        // -----------------------------------------------------------------
        // Return View dengan Data
        // -----------------------------------------------------------------
        return view('livewire.job-search', [
            'jobs' => $jobs,
            'user' => $user,
            'faculties' => $this->faculties, // Computed property
        ]);
    }
}
