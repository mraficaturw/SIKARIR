<?php

namespace App\Livewire;

use App\Models\Internjob;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class JobSearch extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function getFacultiesProperty(): array
    {
        return [
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
    }

    public function render()
    {
        $query = Internjob::with('company');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('company', function ($cq) {
                        $cq->where('company_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get user
        $user = Auth::guard('user_accounts')->user();
        if ($user instanceof UserAccount) {
            $user->load(['favorites', 'appliedJobs']);
        }

        return view('livewire.job-search', [
            'jobs' => $jobs,
            'user' => $user,
            'faculties' => $this->faculties,
        ]);
    }
}
