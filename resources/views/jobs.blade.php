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
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Browse More Job</h1>
        <div class="row g-4">
            @foreach($jobs as $job)
            <div class="col-md-4 col-lg-3 d-flex">
                <div class="card job-card shadow-sm h-100 w-100 wow fadeInUp" data-wow-delay="{{ 0.1 * ($loop->index + 1) }}s">
                    <div class="card-body d-flex flex-column">
                        <img src="{{ $job->logo ? asset('storage/logos/' . $job->logo) : asset('img/com-logo-1.jpg') }}" alt="Logo" class="mb-3 mx-auto" width="60">
                        <h5 class="card-title fw-semibold text-center text-truncate">{{ $job->title }}</h5>
                        <p class="text-muted text-center mb-1 text-truncate"><i class="fa fa-building text-primary me-2"></i> {{ $job->company }}</p>
                        <p class="text-muted text-center mb-3 text-truncate"><i class="fa fa-graduation-cap text-primary me-2"></i> {{ $job->category }}</p>
                        <div class="mt-auto d-flex justify-content-center gap-2">
                            @auth('user_accounts')
                                <form method="POST" action="{{ route('job.favorite.toggle', $job->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm">
                                        @if($user && $user->favorites && $user->favorites->contains($job->id))
                                            <i class="fas fa-heart text-danger me-1"></i>Favorited
                                        @else
                                            <i class="far fa-heart text-primary me-1"></i>Favorite
                                        @endif
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm" onclick="alert('Silakan login untuk menambahkan job ke favorit.')"><i class="far fa-heart text-primary me-1"></i>Favorite</a>
                            @endauth
                            <a href="{{ route('job.detail', $job->id) }}" class="btn btn-success btn-sm">Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Jobs End -->
@endsection
