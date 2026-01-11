<?php

namespace App\Http\Controllers;

use App\Models\companies;
use App\Models\Internjob;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternjobController extends Controller
{
    /**
     * Display welcome page with jobs
     */
    public function index(Request $request)
    {
        // Get search parameters
        $search = $request->get('search');
        $category = $request->get('category');

        // Faculties data - SESUAIKAN DENGAN INTERNJOB FORM
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

        // Get user dengan eager loading
        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        // Jika user login, ensure we have an Eloquent UserAccount instance with relationships
        if ($user instanceof UserAccount && $user->getKey()) {
            $user = UserAccount::with(['favorites', 'appliedJobs'])->find($user->getKey());
        }

        // Icons for categories - SESUAIKAN DENGAN FAKULTAS BARU
        $icons = [
            'Fakultas Teknik' => 'fa-cogs',
            'Fakultas Ekonomi dan Bisnis' => 'fa-chart-line',
            'Fakultas Ilmu Komputer' => 'fa-laptop-code',
            'Fakultas Hukum' => 'fa-gavel',
            'Fakultas Kesehatan' => 'fa-stethoscope',
            'Fakultas Pertanian' => 'fa-seedling',
            'Fakultas Ilmu Sosial dan Politik' => 'fa-users',
            'Fakultas Keguruan dan Ilmu Pendidikan' => 'fa-chalkboard-teacher',
            'Fakultas Agama Islam' => 'fa-mosque',
        ];

        // Query jobs with filters
        $query = Internjob::with('company');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($cq) use ($search) {
                        $cq->where('company_name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $jobs = $query->orderBy('created_at', 'desc')->limit(6)->get();

        // Count jobs per category
        $category_counts = [];
        foreach ($faculties as $key => $name) {
            $category_counts[$key] = Internjob::where('category', $key)->count();
        }

        return view('welcome', compact('jobs', 'user', 'faculties', 'icons', 'category_counts'));
    }

    /**
     * Display all jobs with pagination
     */
    public function jobs(Request $request)
    {
        // Get search parameters
        $search = $request->get('search');
        $category = $request->get('category');

        // Query jobs with filters
        $query = Internjob::with('company');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($cq) use ($search) {
                        $cq->where('company_name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get user dengan eager loading
        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        // Jika user login, load relationships-nya (only if it's an Eloquent model)
        if ($user instanceof UserAccount) {
            $user->load(['favorites', 'appliedJobs']);
        }

        // Faculties data - SESUAIKAN DENGAN INTERNJOB FORM
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

        return view('jobs', compact('jobs', 'user', 'faculties'));
    }

    /**
     * Display job details
     */
    public function show($id)
    {
        $job = Internjob::with('company')->findOrFail($id);

        // Get user dengan eager loading
        $user = Auth::guard('user_accounts')->user();

        // Jika user login, ensure we have an Eloquent UserAccount instance with relationships
        if ($user && $user instanceof UserAccount && $user->getKey()) {
            $user = UserAccount::with(['favorites', 'appliedJobs'])->find($user->getKey());
        }

        return view('job-detail', compact('job', 'user'));
    }

    /**
     * Toggle favorite job
     */
    public function toggleFavorite($id)
    {
        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        $job = Internjob::findOrFail($id);

        // Check if already favorited
        if ($user->favorites()->where('internjob_id', $id)->exists()) {
            $user->favorites()->detach($id);
            $message = 'Job removed from favorites';
            $isFavorited = false;
        } else {
            $user->favorites()->attach($id);
            $message = 'Job added to favorites';
            $isFavorited = true;
        }

        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message,
                'is_favorited' => $isFavorited
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Toggle applied job
     */
    public function toggleApplied($id)
    {
        /** @var UserAccount $user */
        $user = Auth::guard('user_accounts')->user();

        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        $job = Internjob::findOrFail($id);

        // Check if already applied
        if ($user->appliedJobs()->where('internjob_id', $id)->exists()) {
            $user->appliedJobs()->detach($id);
            $message = 'Job removed from applied list';
            $isApplied = false;
        } else {
            $user->appliedJobs()->attach($id, ['applied_at' => now()]);
            $message = 'Job marked as applied';
            $isApplied = true;
        }

        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message,
                'is_applied' => $isApplied
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Display company details
     */
    public function companyDetail($id)
    {
        $company = companies::with('internjobs')->findOrFail($id);

        return view('company-detail', compact('company'));
    }
}
