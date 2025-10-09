<?php

namespace App\Http\Controllers;

use App\Models\internjob;
use Illuminate\Http\Request;

class InternjobController extends Controller
{
    public function index(Request $request)
    {
        $query = internjob::query();

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->whereRaw('LOWER(TRIM(category)) = ?', [strtolower($request->category)]);
        }

        // Search by title or company
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%');
            });
        }

        // Limit to 10 latest jobs for welcome page
        $jobs = $query->latest()->limit(10)->get();

        $faculties = [
            'Fakultas Teknik' => 'Fakultas Teknik',
            'Fakultas Ekonomi dan Bisnis' => 'Fakultas Ekonomi dan Bisnis',
            'Fakultas Ilmu Komputer' => 'Fakultas Ilmu Komputer',
            'Fakultas Hukum' => 'Fakultas Hukum',
            'Fakultas Kesehatan' => 'Fakultas Kesehatan',
            'Fakultas Pertanian' => 'Fakultas Pertanian',
            'Fakultas Ilmu Sosial dan Politik' => 'Fakultas Ilmu Sosial dan Politik',
            'Fakultas Keguruan dan Ilmu Pendidikan' => 'Fakultas Keguruan dan Ilmu Pendidikan',
            'Fakultas Agama Islam' => 'Fakultas Agama Islam',
        ];

        $category_counts = internjob::selectRaw('LOWER(TRIM(category)) as category_key')
            ->selectRaw('COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category_key')
            ->get()
            ->pluck('count', 'category_key');

        $icons = [
            'Fakultas Teknik' => 'fa-cogs',
            'Fakultas Ekonomi dan Bisnis' => 'fa-chart-line',
            'Fakultas Ilmu Komputer' => 'fa-laptop-code',
            'Fakultas Hukum' => 'fa-gavel',
            'Fakultas Kesehatan' => 'fa-heartbeat',
            'Fakultas Pertanian' => 'fa-leaf',
            'Fakultas Ilmu Sosial dan Politik' => 'fa-users',
            'Fakultas Keguruan dan Ilmu Pendidikan' => 'fa-graduation-cap',
            'Fakultas Agama Islam' => 'fa-mosque',
        ];

        $user = auth('user_accounts')->user();

        return view('welcome', compact('jobs', 'faculties', 'category_counts', 'icons', 'user'));
    }

    public function show($id)
    {
        $job = internjob::findOrFail($id);
        $user = auth('user_accounts')->user();
        return view('job-detail', compact('job', 'user'));
    }

    public function jobs(Request $request)
    {
        $query = internjob::query();

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->whereRaw('LOWER(TRIM(category)) = ?', [strtolower($request->category)]);
        }

        // Search by title or company
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->latest()->paginate(10);

        $faculties = [
            'Fakultas Teknik' => 'Fakultas Teknik',
            'Fakultas Ekonomi dan Bisnis' => 'Fakultas Ekonomi dan Bisnis',
            'Fakultas Ilmu Komputer' => 'Fakultas Ilmu Komputer',
            'Fakultas Hukum' => 'Fakultas Hukum',
            'Fakultas Kesehatan' => 'Fakultas Kesehatan',
            'Fakultas Pertanian' => 'Fakultas Pertanian',
            'Fakultas Ilmu Sosial dan Politik' => 'Fakultas Ilmu Sosial dan Politik',
            'Fakultas Keguruan dan Ilmu Pendidikan' => 'Fakultas Keguruan dan Ilmu Pendidikan',
            'Fakultas Agama Islam' => 'Fakultas Agama Islam',
        ];

        $category_counts = internjob::selectRaw('LOWER(TRIM(category)) as category_key')
            ->selectRaw('COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category_key')
            ->get()
            ->pluck('count', 'category_key');

        $icons = [
            'Fakultas Teknik' => 'fa-cogs',
            'Fakultas Ekonomi dan Bisnis' => 'fa-chart-line',
            'Fakultas Ilmu Komputer' => 'fa-laptop-code',
            'Fakultas Hukum' => 'fa-heartbeat',
            'Fakultas Pertanian' => 'fa-leaf',
            'Fakultas Ilmu Sosial dan Politik' => 'fa-users',
            'Fakultas Keguruan dan Ilmu Pendidikan' => 'fa-graduation-cap',
            'Fakultas Agama Islam' => 'fa-mosque',
        ];

        $user = auth('user_accounts')->user();

        return view('jobs', compact('jobs', 'faculties', 'category_counts', 'icons', 'user'));
    }

    public function toggleFavorite($id)
    {
        $user = auth('user_accounts')->user();
        if (!$user) {
            return back()->with('error', 'Silakan login untuk menambahkan job ke favorit.');
        }
        $job = internjob::findOrFail($id);

        if ($user->favorites()->where('internjob_id', $id)->exists()) {
            $user->favorites()->detach($id);
            $message = 'Job removed from favorites.';
        } else {
            $user->favorites()->attach($id);
            $message = 'Job added to favorites.';
        }

        return back()->with('success', $message);
    }

    public function toggleApplied($id)
    {
        $user = auth('user_accounts')->user();
        if (!$user) {
            return back()->with('error', 'Silakan login untuk menandai job sebagai applied.');
        }
        $job = internjob::findOrFail($id);

        if ($user->applied()->where('internjob_id', $id)->exists()) {
            $user->applied()->detach($id);
            $message = 'Job unmarked as applied.';
        } else {
            $user->applied()->attach($id, ['applied_at' => now()]);
            $message = 'Job marked as applied.';
        }

        return back()->with('success', $message);
    }
}
