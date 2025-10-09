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
                            <h1 class="display-3 text-white animated slideInDown mb-4">Penui Syarat Magangmu bersama SIKARIR</h1>
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
            @php $count = $category_counts[strtolower($key)] ?? 0; @endphp
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                <a class="cat-item rounded p-4" href="{{ route('welcome', ['category' => $key]) }}">
                    <i class="fa fa-3x {{ $icons[$key] ?? 'fa-graduation-cap' }} text-primary mb-4"></i>
                    <h6 class="mb-3">{{ $name }}</h6>
                    <p class="mb-0">{{ $count }} Vacancy</p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Category End -->

<!-- Jobs Start -->
<div class="container-xxl py-5">
    <div class="container">
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">New Jobs</h1>
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    @foreach($jobs as $job)
                        <div class="job-item p-4 mb-4">
                            <div class="row g-4">
                                <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                    <img class="flex-shrink-0 img-fluid border rounded" src="{{ $job->logo ? asset('storage/' . $job->logo) : asset('img/com-logo-1.jpg') }}" alt="" style="width: 80px; height: 80px;">
                                    <div class="text-start ps-4">
                                        <h5 class="mb-3">{{ $job->title }}</h5>
                                        <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $job->location }}</span>
                                        <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>Internship</span>
                                        <span class="text-truncate me-0"><i class="far fa-money-bill-alt text-primary me-2"></i>Rp {{ number_format($job->salary_min) }} - Rp {{ number_format($job->salary_max) }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                    <div class="d-flex mb-3">
                                        @auth('user_accounts')
                                            <form method="POST" action="{{ route('job.favorite.toggle', $job->id) }}" class="me-3">
                                                @csrf
                                                <button type="submit" class="btn btn-light btn-square">
                                                    @if($user && $user->favorites->contains($job->id))
                                                        <i class="fas fa-heart text-danger"></i>
                                                    @else
                                                        <i class="far fa-heart text-primary"></i>
                                                    @endif
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('job.applied.toggle', $job->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">
                                                    @if($user && $user->applied->contains($job->id))
                                                        Marked Applied
                                                    @else
                                                        Mark as Applied
                                                    @endif
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-light btn-square me-3" onclick="alert('Silakan login untuk menambahkan job ke favorit.')"><i class="far fa-heart text-primary"></i></a>
                                            <a href="{{ route('login') }}" class="btn btn-primary" onclick="alert('Silakan login untuk menandai job sebagai applied.')">Mark as Applied</a>
                                        @endauth
                                        <a class="btn btn-primary ms-3" href="{{ route('job.detail', $job->id) }}">Details</a>
                                    </div>
                                    <small class="text-truncate"><i class="far fa-calendar-alt text-primary me-2"></i>Date Line: {{ $job->deadline ? $job->deadline->format('d M, Y') : 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-4">
                        <a href="{{ route('jobs') }}" class="btn btn-primary py-3 px-5">Browse More Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Jobs End -->
@endsection
