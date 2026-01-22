<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Vacancy;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ============================================================================
 * VACANCY CONTROLLER
 * ============================================================================
 * Controller utama untuk menampilkan dan mengelola lowongan kerja/magang.
 * 
 * Fitur utama:
 * - Menampilkan halaman utama dengan lowongan terbaru
 * - Menampilkan daftar semua lowongan dengan pagination
 * - Menampilkan detail lowongan
 * - Menampilkan detail perusahaan
 * - Toggle favorit dan status lamaran
 * 
 * Controller ini banyak digunakan oleh route publik (tanpa login)
 * kecuali untuk fitur favorit dan lamaran yang butuh autentikasi.
 * ============================================================================
 */
class VacancyController extends Controller
{
    /**
     * -------------------------------------------------------------------------
     * Halaman Utama (Welcome Page)
     * -------------------------------------------------------------------------
     * Method ini menampilkan halaman utama SIKARIR dengan:
     * - 6 lowongan terbaru
     * - Daftar kategori fakultas dengan icon
     * - Jumlah lowongan per kategori
     * - Fitur pencarian
     * 
     * @param Request $request Data request berisi parameter search dan category
     * @return \Illuminate\View\View Halaman welcome dengan data lowongan
     */
    public function index(Request $request)
    {
        // -----------------------------------------------------------------
        // Ambil Parameter Pencarian
        // -----------------------------------------------------------------
        // Parameter ini digunakan untuk filter lowongan
        $search = $request->get('search');      // Kata kunci pencarian
        $category = $request->get('category');   // Filter berdasarkan fakultas

        // -----------------------------------------------------------------
        // Data Fakultas dari Config
        // -----------------------------------------------------------------
        // Menggunakan config terpusat untuk menghindari duplikasi
        $facultiesConfig = config('sikarir.faculties');
        $faculties = collect($facultiesConfig)->mapWithKeys(fn($data, $key) => [$key => $data['label']])->toArray();
        $icons = collect($facultiesConfig)->mapWithKeys(fn($data, $key) => [$key => $data['icon']])->toArray();

        // -----------------------------------------------------------------
        // Ambil Data User (Jika Login)
        // -----------------------------------------------------------------
        // Cek apakah ada user yang login untuk keperluan:
        // - Menampilkan status favorit di setiap card lowongan
        // - Menampilkan status sudah apply di setiap card lowongan
        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        // Jika user login, load relasi favorites dan appliedJobs
        // Ini menggunakan eager loading untuk menghindari N+1 query problem
        if ($user instanceof UserAccount && $user->getKey()) {
            $user = UserAccount::with(['favorites', 'appliedJobs'])->find($user->getKey());
        }

        // -----------------------------------------------------------------
        // Query Lowongan dengan Filter (menggunakan scopeSearch)
        // -----------------------------------------------------------------
        // Jika tidak ada search/filter, gunakan cached data untuk performa optimal
        // Jika ada search/filter, query langsung (karena dynamic)
        if (empty($search) && empty($category)) {
            $jobs = Vacancy::getCachedLatest(config('sikarir.pagination.welcome_jobs_limit', 6));
        } else {
            $jobs = Vacancy::with('company')
                ->search($search, $category)
                ->orderBy('created_at', 'desc')
                ->limit(config('sikarir.pagination.welcome_jobs_limit', 6))
                ->get();
        }

        // -----------------------------------------------------------------
        // Hitung Jumlah Lowongan per Kategori (Cached)
        // -----------------------------------------------------------------
        // Digunakan untuk menampilkan badge jumlah di setiap kategori
        // Menggunakan cache karena data ini jarang berubah
        $category_counts = Vacancy::getCachedCategoryCounts();

        return view('welcome', compact('jobs', 'user', 'faculties', 'icons', 'category_counts'));
    }

    /**
     * -------------------------------------------------------------------------
     * Halaman Daftar Semua Lowongan
     * -------------------------------------------------------------------------
     * Method ini menampilkan semua lowongan dengan:
     * - Pagination (10 lowongan per halaman)
     * - Fitur pencarian dan filter kategori
     * - Status favorit dan apply untuk user yang login
     * 
     * @param Request $request Data request berisi parameter search dan category
     * @return \Illuminate\View\View Halaman jobs dengan pagination
     */
    public function jobs(Request $request)
    {
        // Ambil parameter pencarian
        $search = $request->get('search');
        $category = $request->get('category');

        // -----------------------------------------------------------------
        // Query Lowongan dengan Filter (menggunakan scopeSearch)
        // -----------------------------------------------------------------
        $jobs = Vacancy::with('company')
            ->search($search, $category)
            ->orderBy('created_at', 'desc')
            ->paginate(config('sikarir.pagination.jobs_per_page', 10));
        
        // -----------------------------------------------------------------
        // Ambil Data User (Jika Login)
        // -----------------------------------------------------------------
        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        // Load relasi jika user adalah instance UserAccount
        if ($user instanceof UserAccount) {
            $user->load(['favorites', 'appliedJobs']);
        }

        // Daftar fakultas untuk dropdown filter (dari config terpusat)
        $faculties = collect(config('sikarir.faculties'))->mapWithKeys(fn($data, $key) => [$key => $data['label']])->toArray();

        return view('jobs', compact('jobs', 'user', 'faculties'));
    }

    /**
     * -------------------------------------------------------------------------
     * Halaman Detail Lowongan
     * -------------------------------------------------------------------------
     * Method ini menampilkan informasi lengkap satu lowongan:
     * - Judul dan nama perusahaan
     * - Lokasi dan rentang gaji
     * - Deskripsi lengkap
     * - Tanggung jawab dan kualifikasi
     * - Deadline dan link untuk apply
     * - Status favorit dan apply (jika user login)
     * 
     * @param int $id ID lowongan yang akan ditampilkan
     * @return \Illuminate\View\View Halaman detail lowongan
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika ID tidak ditemukan
     */
    public function show($id)
    {
        // Cari lowongan berdasarkan ID dengan relasi company
        // findOrFail akan menampilkan 404 jika tidak ditemukan
        $job = Vacancy::with('company')->findOrFail($id);

        // Ambil data user jika login
        $user = Auth::guard('user_accounts')->user();

        // Load relasi favorit dan lamaran untuk menampilkan status tombol
        if ($user && $user instanceof UserAccount && $user->getKey()) {
            $user = UserAccount::with(['favorites', 'appliedJobs'])->find($user->getKey());
        }

        return view('job-detail', compact('job', 'user'));
    }

    /**
     * -------------------------------------------------------------------------
     * Toggle Status Favorit Lowongan
     * -------------------------------------------------------------------------
     * Method ini menambah atau menghapus lowongan dari daftar favorit user.
     * 
     * Cara kerja (toggle):
     * - Jika belum difavoritkan → tambahkan ke favorit
     * - Jika sudah difavoritkan → hapus dari favorit
     * 
     * Method ini mendukung dua jenis response:
     * - JSON response untuk AJAX/Livewire request
     * - Redirect response untuk form biasa
     * 
     * @param int $id ID lowongan yang akan di-toggle
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggleFavorite($id)
    {
        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        // -----------------------------------------------------------------
        // Cek Autentikasi
        // -----------------------------------------------------------------
        // Pengguna harus login untuk menggunakan fitur ini
        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        // Pastikan lowongan ada di database
        $job = Vacancy::findOrFail($id);

        // -----------------------------------------------------------------
        // Toggle Status Favorit
        // -----------------------------------------------------------------
        // Cek apakah lowongan sudah ada di daftar favorit
        if ($user->favorites()->where('vacancy_id', $id)->exists()) {
            // Sudah ada → hapus dari favorit (detach)
            $user->favorites()->detach($id);
            $message = 'Job removed from favorites';
            $isFavorited = false;
        } else {
            // Belum ada → tambahkan ke favorit (attach)
            $user->favorites()->attach($id);
            $message = 'Job added to favorites';
            $isFavorited = true;
        }

        // -----------------------------------------------------------------
        // Kirim Response
        // -----------------------------------------------------------------
        // JSON response untuk AJAX request
        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message,
                'is_favorited' => $isFavorited
            ]);
        }

        // Redirect response untuk form biasa
        return back()->with('success', $message);
    }

    /**
     * -------------------------------------------------------------------------
     * Toggle Status Lamaran
     * -------------------------------------------------------------------------
     * Method ini menandai atau menghapus status "sudah apply" pada lowongan.
     * 
     * Cara kerja (toggle):
     * - Jika belum apply → tandai sebagai sudah apply
     * - Jika sudah apply → hapus tanda apply
     * 
     * Catatan: Ini hanya untuk tracking internal, user tetap perlu
     * apply melalui link external yang disediakan perusahaan.
     * 
     * Method ini mendukung dua jenis response:
     * - JSON response untuk AJAX/Livewire request
     * - Redirect response untuk form biasa
     * 
     * @param int $id ID lowongan yang akan di-toggle
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggleApplied($id)
    {
        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        // Cek autentikasi
        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        // Pastikan lowongan ada di database
        $job = Vacancy::findOrFail($id);

        // -----------------------------------------------------------------
        // Toggle Status Apply
        // -----------------------------------------------------------------
        if ($user->appliedJobs()->where('vacancy_id', $id)->exists()) {
            // Sudah apply → hapus dari daftar (detach)
            $user->appliedJobs()->detach($id);
            $message = 'Job removed from applied list';
            $isApplied = false;
        } else {
            // Belum apply → tambahkan ke daftar dengan timestamp
            // Pivot applied_at mencatat waktu apply
            $user->appliedJobs()->attach($id, ['applied_at' => now()]);
            $message = 'Job marked as applied';
            $isApplied = true;
        }

        // Kirim response sesuai tipe request
        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message,
                'is_applied' => $isApplied
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * -------------------------------------------------------------------------
     * Halaman Detail Perusahaan
     * -------------------------------------------------------------------------
     * Method ini menampilkan informasi lengkap perusahaan:
     * - Nama dan logo perusahaan
     * - Alamat dan kontak (email, telepon, website)
     * - Deskripsi perusahaan
     * - Daftar lowongan yang tersedia dari perusahaan ini
     * 
     * @param int $id ID perusahaan yang akan ditampilkan
     * @return \Illuminate\View\View Halaman detail perusahaan
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika ID tidak ditemukan
     */
    public function companyDetail($id)
    {
        // Cari perusahaan dengan relasi lowongan (menggunakan cache)
        // Cached query untuk performa optimal
        $company = Company::getCachedCompany($id);

        // Jika company tidak ditemukan, return 404
        if (!$company) {
            abort(404);
        }

        return view('company-detail', compact('company'));
    }
}
