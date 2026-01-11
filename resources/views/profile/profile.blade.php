@extends('layouts.app')

@section('content')
<!-- Profile Header -->
<section style="background: var(--gradient-hero); padding: 3rem 0;">
    <div class="container">
        <div class="d-flex align-items-center gap-4 flex-wrap" data-animate>
            <a href="{{ route('profile.edit') }}" class="position-relative" title="Edit Profil" style="text-decoration: none;">
                <div class="rounded-circle bg-white p-1" style="width: 90px; height: 90px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                        <i class="fa fa-user text-primary" style="font-size: 2.5rem;" aria-hidden="true"></i>
                    @endif
                </div>
                <div class="position-absolute" style="bottom: 0; right: 0; background: var(--primary); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white;">
                    <i class="fa fa-pencil text-white" style="font-size: 0.75rem;"></i>
                </div>
            </a>
            <div>
                <h1 class="text-white fw-bold mb-1">Hi, {{ $user->name }}!</h1>
                <p class="text-white-50 mb-0"><i class="fa fa-envelope me-2" aria-hidden="true"></i>{{ $user->email }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="section-modern" style="padding-top: 2rem;">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success mb-4" role="alert">
                <i class="fa fa-check-circle me-2" aria-hidden="true"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('status'))
            <div class="alert alert-info mb-4" role="alert">
                <i class="fa fa-info-circle me-2" aria-hidden="true"></i>{{ session('status') }}
            </div>
        @endif

        <!-- Dream Jobs Section -->
        <div class="mb-5">
            <div class="d-flex align-items-center gap-3 mb-4" data-animate>
                <div class="icon-wrapper" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-heart text-danger" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-0">Dream Jobs (Favorites)</h3>
                    <p class="text-muted small mb-0">Jobs you've saved for later</p>
                </div>
            </div>
            
            @if($favorites->isEmpty())
                <div class="card-modern text-center py-5" data-animate>
                    <i class="fa fa-heart text-muted mb-3" style="font-size: 3rem; opacity: 0.3;" aria-hidden="true"></i>
                    <p class="text-muted mb-3">You haven't saved any jobs yet</p>
                    <a href="{{ route('jobs') }}" class="btn-primary-modern">
                        <i class="fa fa-search me-2" aria-hidden="true"></i>Explore Jobs
                    </a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($favorites as $job)
                        <div class="col-md-6" data-animate>
                            <div class="job-card-modern h-100">
                                <div class="job-header">
                                    <img class="company-logo" src="{{ $job->logo_url }}" alt="{{ $job->company->company_name ?? 'Company' }}" loading="lazy" width="60" height="60">
                                    <div style="flex: 1; min-width: 0;">
                                        <h5 class="job-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $job->title }}</h5>
                                        <a href="{{ route('company.detail', $job->company_id) }}" class="company-name text-decoration-none" title="Lihat detail perusahaan">
                                            <i class="fa fa-building" aria-hidden="true"></i>
                                            {{ $job->company->company_name ?? 'Unknown Company' }}
                                        </a>
                                    </div>
                                </div>
                                <div class="job-meta">
                                    <span class="job-meta-item">
                                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                        @if(mb_strlen($job->category) > 18)
                                            {{ mb_substr($job->category, 0, 18) . '...' }}
                                        @else
                                            {{ $job->category }}
                                        @endif
                                    </span>
                                    <span class="job-meta-item">
                                        <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                                        {{ $job->location ?? 'Indonesia' }}
                                    </span>
                                    <span class="job-meta-item">
                                        <i class="fa fa-money-bill-wave" aria-hidden="true"></i>
                                        Rp {{ number_format((int)$job->salary_min, 0, ',', '.') }} - {{ number_format((int)$job->salary_max, 0, ',', '.') }}
                                    </span>
                                    @if($job->deadline < now())
                                        <span class="job-meta-item text-danger" style="font-weight: 600;">
                                            <i class="far fa-calendar-times" aria-hidden="true"></i>
                                            Tidak menerima pendaftaran lagi
                                        </span>
                                    @else
                                        <span class="job-meta-item">
                                            <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                            Deadline: {{ $job->deadline->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="job-footer">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('job.detail', $job->id) }}" class="btn-primary-modern" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                            View <i class="fa fa-arrow-right ms-1" aria-hidden="true"></i>
                                        </a>
                                        @if($job->apply_url)
                                            <a href="{{ $job->apply_url }}" target="_blank" class="btn-success-modern" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                                Apply <i class="fa fa-external-link-alt ms-1" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Applied Jobs Section -->
        <div class="mb-5">
            <div class="d-flex align-items-center gap-3 mb-4" data-animate>
                <div class="icon-wrapper" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-paper-plane text-success" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-0">Applied Jobs</h3>
                    <p class="text-muted small mb-0">Track your job applications</p>
                </div>
            </div>
            
            @if($appliedJobs->isEmpty())
                <div class="card-modern text-center py-5" data-animate>
                    <i class="fa fa-paper-plane text-muted mb-3" style="font-size: 3rem; opacity: 0.3;" aria-hidden="true"></i>
                    <p class="text-muted mb-3">You haven't applied to any jobs yet</p>
                    <a href="{{ route('jobs') }}" class="btn-primary-modern">
                        <i class="fa fa-search me-2" aria-hidden="true"></i>Find Jobs
                    </a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($appliedJobs as $job)
                        <div class="col-md-6" data-animate>
                            <div class="job-card-modern h-100">
                                <div class="job-header">
                                    <img class="company-logo" src="{{ $job->logo_url }}" alt="{{ $job->company->company_name ?? 'Company' }}" loading="lazy" width="60" height="60">
                                    <div style="flex: 1; min-width: 0;">
                                        <h5 class="job-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $job->title }}</h5>
                                        <a href="{{ route('company.detail', $job->company_id) }}" class="company-name text-decoration-none" title="Lihat detail perusahaan">
                                            <i class="fa fa-building" aria-hidden="true"></i>
                                            {{ $job->company->company_name ?? 'Unknown Company' }}
                                        </a>
                                    </div>
                                </div>
                                <div class="job-meta">
                                    <span class="job-meta-item">
                                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                        @if(mb_strlen($job->category) > 18)
                                            {{ mb_substr($job->category, 0, 18) . '...' }}
                                        @else
                                            {{ $job->category }}
                                        @endif
                                    </span>
                                    <span class="job-meta-item">
                                        <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                                        {{ $job->location ?? 'Indonesia' }}
                                    </span>
                                    <span class="job-meta-item">
                                        <i class="fa fa-money-bill-wave" aria-hidden="true"></i>
                                        Rp {{ number_format((int)$job->salary_min, 0, ',', '.') }} - {{ number_format((int)$job->salary_max, 0, ',', '.') }}
                                    </span>
                                    <span class="job-meta-item">
                                        <i class="fa fa-calendar-check" aria-hidden="true"></i>
                                        Applied: {{ \Carbon\Carbon::parse($job->pivot->applied_at)->format('d M, Y') }}
                                    </span>
                                    @if($job->deadline < now())
                                        <span class="job-meta-item text-danger" style="font-weight: 600;">
                                            <i class="far fa-calendar-times" aria-hidden="true"></i>
                                            Tidak menerima pendaftaran lagi
                                        </span>
                                    @else
                                        <span class="job-meta-item">
                                            <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                            Deadline: {{ $job->deadline->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="job-footer">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('job.detail', $job->id) }}" class="btn-primary-modern" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                            View <i class="fa fa-arrow-right ms-1" aria-hidden="true"></i>
                                        </a>
                                        @if($job->apply_url)
                                            <a href="{{ $job->apply_url }}" target="_blank" class="btn-success-modern" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                                See Progress <i class="fa fa-external-link-alt ms-1" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Account Settings -->
        <div class="card-modern" data-animate>
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="icon-wrapper" style="width: 48px; height: 48px; background: rgba(0, 176, 116, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-cog text-primary" aria-hidden="true"></i>
                </div>
                <h3 class="h5 fw-bold mb-0">Account Settings</h3>
            </div>
            
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('profile.change-password') }}" class="btn btn-warning">
                    <i class="fa fa-key me-2" aria-hidden="true"></i>Change Password
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-sign-out-alt me-2" aria-hidden="true"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection