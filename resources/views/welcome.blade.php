@extends('layouts.app')

@section('content')
<!-- Carousel Start -->
<div class="container-fluid p-0">
    <div class="owl-carousel header-carousel position-relative">
        <div class="owl-carousel-item position-relative">
            <img class="img-fluid" src="{{ asset('img/unsika.jpg') }}" alt="">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(43, 57, 64, .5);">
                <div class="container">
                    <div class="row justify-content-start">
                        <div class="col-10 col-lg-8">
                            <h1 class="display-3 text-white animated slideInDown mb-4">SIKARIR</h1>
                            <p class="fs-5 fw-medium text-white mb-4 pb-2">Membantu Mahasiswa Unsika Menemukan Peluang Magang Terbaik</p>
                            <a href="{{ route('jobs') }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Search A Job</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="owl-carousel-item position-relative">
            <img class="img-fluid" src="{{ asset('img/unsika.jpg') }}" alt="">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(43, 57, 64, .5);">
                <div class="container">
                    <div class="row justify-content-start">
                        <div class="col-10 col-lg-8">
                            <h1 class="display-3 text-white animated slideInDown mb-4">Penuhi Syarat Magangmu bersama SIKARIR</h1>
                            <p class="fs-5 fw-medium text-white mb-4 pb-2">Platform Terpusat untuk Mencari Lowongan Magang Sesuai Bidang Peminatan</p>
                            <a href="{{ route('jobs') }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Search A Job</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Carousel End -->

<!-- Search Start -->
<div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
    <div class="container">
        <form action="{{ route('welcome') }}" method="GET">
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

<!-- Category Start -->
<div class="container-xxl py-5">
    <div class="container">
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Cari Sesuai Jurusan</h1>
        <div class="row g-4">
            @foreach($faculties as $key => $name)
            @php
                $count = $category_counts[$key] ?? 0;
            @endphp
            <div class="col-lg-3 col-sm-6 d-flex">
                <a class="card category-card shadow-sm h-100 w-100 text-decoration-none wow fadeInUp" data-wow-delay="{{ 0.1 * ($loop->index + 1) }}s" href="{{ route('welcome', ['category' => $key]) }}">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                        <i class="fa fa-3x {{ $icons[$key] ?? 'fa-graduation-cap' }} text-primary mb-4"></i>
                        <h6 class="mb-3 text-truncate">{{ $name }}</h6>
                        <p class="mb-0">{{ $count }} Vacancy</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Category End -->

<!-- Jobs Start -->
<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">Latest Jobs</h2>
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
<!-- Jobs End -->
@endsection