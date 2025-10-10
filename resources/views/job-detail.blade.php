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
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-white rounded shadow-sm p-5 border">
                    <h2 class="fw-semibold mb-4">{{ $job->title }}</h2>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fa fa-building text-primary me-2"></i><strong>Company:</strong> <span class="text-muted">{{ $job->company }}</span></p>
                            <p class="mb-2"><i class="fa fa-map-marker-alt text-primary me-2"></i><strong>Location:</strong> <span class="text-muted">{{ $job->location ?? 'Not specified' }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><i class="fa fa-graduation-cap text-primary me-2"></i><strong>Category:</strong> <span class="text-muted">{{ $job->category }}</span></p>
                            <p class="mb-2"><i class="fa fa-calendar text-primary me-2"></i><strong>Posted:</strong> <span class="text-muted">{{ $job->created_at->format('M d, Y') }}</span></p>
                        </div>
                    </div>
                    <h4 class="fw-semibold mb-3">Description</h4>
                    <p class="text-muted mb-4">{{ $job->description }}</p>
                    @if($job->requirements)
                    <h4 class="fw-semibold mb-3">Requirements</h4>
                    <p class="text-muted mb-4">{{ $job->requirements }}</p>
                    @endif
                    @if($job->apply_url)
                    <h4 class="fw-semibold mb-3">How to Apply</h4>
                    <p><a href="{{ $job->apply_url }}" target="_blank" class="btn btn-primary">Apply Here</a></p>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-white rounded shadow-sm p-4 border mb-4">
                    <h4 class="fw-semibold mb-4">Job Summary</h4>
                    <p class="mb-3"><i class="fa fa-building text-primary me-2"></i><strong>Company:</strong> <span class="text-muted">{{ $job->company }}</span></p>
                    <p class="mb-3"><i class="fa fa-map-marker-alt text-primary me-2"></i><strong>Location:</strong> <span class="text-muted">{{ $job->location ?? 'Not specified' }}</span></p>
                    <p class="mb-3"><i class="fa fa-graduation-cap text-primary me-2"></i><strong>Category:</strong> <span class="text-muted">{{ $job->category }}</span></p>
                    <p class="mb-3"><i class="fa fa-calendar text-primary me-2"></i><strong>Posted:</strong> <span class="text-muted">{{ $job->created_at->format('M d, Y') }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-12">
        @auth('user_accounts')
            <form method="POST" action="{{ route('job.applied.toggle', $job->id) }}">
                @csrf
                <button type="submit" class="btn btn-success w-100">
                    @if($user && $user->appliedJobs && $user->appliedJobs->contains($job->id))
                        Marked Applied
                    @else
                        Mark as Applied
                    @endif
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-success w-100" onclick="alert('Silakan login untuk menandai job sebagai applied.')">Mark as Applied</a>
        @endauth
    </div>
</div>

<!-- Di bagian tombol favorite -->
<div class="d-flex justify-content-start align-items-center mt-4 gap-3">
    <a class="btn btn-outline-primary" href="{{ route('jobs') }}">Back to Jobs</a>
    @auth('user_accounts')
        <form method="POST" action="{{ route('job.favorite.toggle', $job->id) }}">
            @csrf
            <button type="submit" class="btn btn-light btn-square">
                @if($user && $user->favorites && $user->favorites->contains($job->id))
                    <i class="fas fa-heart text-danger"></i>
                @else
                    <i class="far fa-heart text-primary"></i>
                @endif
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="btn btn-light btn-square" onclick="alert('Silakan login untuk menambahkan job ke favorit.')"><i class="far fa-heart text-primary"></i></a>
    @endauth
</div>
<!-- Job Detail End -->
@endsection
