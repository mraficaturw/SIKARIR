@extends('layouts.app')

@section('content')
<!-- Header Start -->
<div class="container-xxl py-5 bg-dark page-header mb-5">
    <div class="container my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Job Detail</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Home</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Job Detail</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Header End -->

<!-- Job Detail Start -->
        <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row gy-5 gx-4">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center mb-5">
                            <img class="flex-shrink-0 img-fluid border rounded mb-3"
                                src="{{ $job->logo ? asset('storage/' . $job->logo) : asset('img/com-logo-1.jpg') }}"
                                alt="Logo" style="width: 100px; height: 100px;">
                            <div class="text-start ps-4">
                                <h3 class="mb-3">{{ $job->title }}</h3>
                                <span class="text-truncate me-3"><i class="fa-solid fa-building text-primary me-2"></i> {{ $job->company }}</span>
                                    <span class="text-truncate me-3"><i class="fa fa- {{ $icons[$job->category] ?? 'fa-graduation-cap' }} text-primary me-2"></i>
                                        @if(mb_strlen($job->category) > 23)
                                            {{ mb_substr($job->category, 0, 23) . '...' }}
                                        @else
                                            {{ $job->category }}
                                        @endif
                                    </span>
                                    <span class="text-truncate me-0">
                                        <i class="far fa-money-bill-alt text-primary me-2"></i>
                                        Rp {{ number_format((int)$job->salary_min, 0, ',', '.') }} - Rp {{ number_format((int)$job->salary_max, 0, ',', '.') }}
                                    </span>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h4 class="mb-3">Job description</h4>
                            <p>{{ $job->description }}</p>
                            <h4 class="mb-3">Responsibility</h4>
                            <p>{{ $job->responsibility }}</p>
                            <h4 class="mb-3">Qualifications</h4>
                            <p>{{ $job->qualifications }}</p>
                        </div>
        
                        <div class="col-12">
                            <h4 class="mb-4">Apply For The Job</h4>
                            <p><a href="{{ $job->apply_url }}" target="_blank" class="text-secondary">Apply Here : {{ $job->apply_url }}</a></p>
                            @auth('user_accounts')
                                @if(auth('user_accounts')->user()->hasVerifiedEmail())
                                    <form method="POST" action="{{ route('job.applied.toggle', $job->id) }}">
                                        @csrf
                                            @if($user && $user->appliedJobs && $user->appliedJobs->contains($job->id))
                                                <button type="submit" class="btn btn-danger w-100">Remove Apply Mark</button>
                                            @else
                                                <button type="submit" class="btn btn-success w-100">Mark as Applied</button>
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('verification.notice') }}" class="btn btn-success w-100" onclick="alert('Silakan verifikasi email untuk menandai job sebagai applied.')">Mark as Applied</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success w-100" onclick="alert('Silakan login untuk menandai job sebagai applied.')">Mark as Applied</a>
                            @endauth
                        </div>
                    </div>
        
                    <div class="col-lg-4">
                        <div class="bg-light rounded p-5 mb-4 wow slideInUp" data-wow-delay="0.1s">
                            <h4 class="mb-4">Job Summary</h4>
                            <p><i class="fa fa-angle-right text-primary me-2"></i>Published On : {{ $job->created_at->format('d-m-Y') }}</p>
                            <p><i class="fa fa-angle-right text-primary me-2"></i>Last Update : {{ $job->updated_at->format('d-m-Y') }}</p>
                            <p><i class="fa fa-angle-right text-primary me-2"></i>Max Salary : Rp {{ number_format((int)$job->salary_max, 0, ',', '.') }}</p>
                            <p><i class="fa fa-angle-right text-primary me-2"></i>Location : {{ $job->location }}</p>
                            <p class="mb-0"><i class="fa fa-angle-right text-primary me-2"></i>Deadline: {{ $job->deadline->format('d-m-Y') }}</p>
                        </div>
                    </div>

                    <!-- Di bagian tombol favorite -->
                    <div class="d-flex justify-content-start align-items-center mt-5 gap-3">
                        <a class="btn btn-outline-primary" href="{{ route('jobs') }}">Back to Jobs</a>
                        @auth('user_accounts')
                            @if(auth('user_accounts')->user()->hasVerifiedEmail())
                                <form method="POST" action="{{ route('job.favorite.toggle', $job->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-square">
                                       @if($user && $user->favorites && $user->favorites->contains($job->id))
                                            <i class="far fa-solid fa-heart text-primary"></i>
                                        @else
                                            <i class="far fa-heart text-primary"></i>
                                        @endif
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('verification.notice') }}" class="btn btn-light btn-square" onclick="alert('Silakan verifikasi email untuk menambahkan job ke favorit.')"><i class="far fa-heart text-primary"></i></a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-light btn-square" onclick="alert('Silakan login untuk menambahkan job ke favorit.')"><i class="far fa-heart text-primary"></i></a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
<!-- Job Detail End -->
@endsection

