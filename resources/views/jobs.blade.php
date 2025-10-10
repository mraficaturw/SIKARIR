@extends('layouts.app')

@section('content')
<!-- Search Start -->
<div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
    <div class="container">
        <form action="{{ route('jobs') }}" method="GET">
            <div class="row g-2">
                <div class="col-md-10">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control border-0" placeholder="Keyword" value="{{ request('search') }}" />
                        </div>
                        <div class="col-md-6">
                            <select name="category" class="form-select border-0">
                                <option value="">Cari Sesuai Jurusan</option>
                                @foreach($faculties as $key => $name)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark border-0 w-100" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Search End -->

<!-- Jobs Start -->
<div class="container-xxl py-5">
    <div class="container">
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">All Jobs</h1>
        <div class="row g-4">
            @foreach($jobs as $job)
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="job-item bg-light rounded p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 img-fluid border rounded" src="{{ asset('img/com-logo-1.jpg') }}" alt="" style="width: 80px; height: 80px;">
                                <div class="text-start ps-4">
                                    <h5 class="mb-3">{{ $job->title }}</h5>
                                    <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $job->company }}</span>
                                    <span class="text-truncate me-3"><i class="far fa-calendar-alt text-primary me-2"></i>{{ $job->category }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row g-2">
                                <div class="col-6">
                                    @auth('user_accounts')
                                        <form method="POST" action="{{ route('job.favorite.toggle', $job->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-light btn-square w-100">
                                                @if($user && $user->favorites && $user->favorites->contains($job->id))
                                                    <i class="fas fa-heart text-danger"></i>
                                                @else
                                                    <i class="far fa-heart text-primary"></i>
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-light btn-square w-100" onclick="alert('Silakan login untuk menambahkan job ke favorit.')"><i class="far fa-heart text-primary"></i></a>
                                    @endauth
                                </div>
                                <div class="col-6">
                                    @auth('user_accounts')
                                        <form method="POST" action="{{ route('job.applied.toggle', $job->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100">
                                                @if($user && $user->appliedJobs && $user->appliedJobs->contains($job->id))
                                                    Applied
                                                @else
                                                    Apply
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary w-100" onclick="alert('Silakan login untuk menandai job sebagai applied.')">Apply</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <a class="btn btn-primary w-100" href="{{ route('job.detail', $job->id) }}">Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($jobs->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $jobs->links() }}
        </div>
        @endif
    </div>
</div>
<!-- Jobs End -->
@endsection
