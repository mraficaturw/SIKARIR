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
                            <a href="{{ route('jobs') }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInRight">Search A Job</a>
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

<!-- Category Start -->
<div class="container-xxl py-5">
    <div class="container">
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Cari Sesuai Jurusan</h1>
        <div class="row g-4">
             @foreach($faculties as $key => $name)
                @php
                    $count = $category_counts[$key] ?? 0;
                @endphp
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <a class="cat-item rounded p-4" href="">
                        <i class="fa fa-3x {{ $icons[$key] ?? 'fa-graduation-cap' }} text-primary mb-4"></i>
                        <h6 class="mb-3"> 
                            @if(mb_strlen($name) > 23)
                                            {{ mb_substr($name, 0, 23) . '...' }}
                                        @else
                                            {{ $name }}
                                        @endif 
                        </h6>
                        <p class="mb-0">{{ $count }} Vacancy</p>
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
    <div class="tab-content">
        <div class="tab-pane fade show p-0 active">
        @foreach($jobs as $job)
                <div class="job-item p-4 mb-4">
                    <div class="row g-4">
                        <div class="col-sm-12 col-md-8 d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid border rounded mb-3"
                                src="{{ $job->logo ? asset('storage/logos/' . $job->logo) : asset('img/com-logo-1.jpg') }}"
                                alt="Logo"
                                width="60">
                                <div class="text-start ps-4">
                                    <h5 class="mb-3">{{ $job->title }}</h5>
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
                        <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                            <div class="d-flex mb-3">
                                @auth('user_accounts')
                                    @if(auth('user_accounts')->user()->hasVerifiedEmail())
                                        <form method="POST" action="{{ route('job.favorite.toggle', $job->id) }}">
                                        @csrf
                                            <button type="submit" class="btn btn-light btn-square me-3">
                                                @if($user && $user->favorites && $user->favorites->contains($job->id))
                                                    <i class="far fa-solid fa-heart text-primary"></i>
                                                @else
                                                    <i class="far fa-heart text-primary"></i>
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('verification.notice') }}" class="btn btn-light btn-square me-3" onclick="alert('Silakan verifikasi email untuk menambahkan job ke favorit.')">
                                            <i class="far fa-heart text-primary me-1"></i>
                                        </a>
                                    @endif
                                    @else
                                    <a href="{{ route('login') }}" class="btn btn-light btn-square me-3" onclick="alert('Silakan login untuk menambahkan job ke favorit.')">
                                        <i class="far fa-heart text-primary me-1"></i>
                                    </a>
                                @endauth
                                <a class="btn btn-primary" href="{{ route('job.detail', $job->id) }}">Details</a>
                            </div>
                                <small class="text-truncate"><i class="far fa-calendar-alt text-primary me-2"></i>Deadline : {{ $job->deadline->format('d-m-Y') }}</small>
                            </div>
                    </div>
                </div>
        @endforeach
        </div>
    </div>
    <div class="text-center">
        <a class="btn btn-primary py-3 px-5 mb-5" href="{{ route('jobs') }}">Browse More Jobs</a>
    </div>
</div>
<!-- Jobs End -->
@endsection