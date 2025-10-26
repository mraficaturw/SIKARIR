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
<div class="container my-5" style="min-height: 60vh;">
    <h2 class="text-center fw-bold mb-4">Follow Your Dream Career Path!</h2>
    <div class="tab-content"  style="overflow-y: auto; max-height: 400px;">
        <div class="tab-pane fade show p-0 active">
        @foreach($jobs as $job)
                <div class="job-item p-4 mb-4">
                    <div class="row g-4">
                        <div class="col-sm-12 col-md-8 d-flex align-items-center">
                            <img class="flex-shrink-0 img-fluid border rounded" {{ $job->logo ? asset('storage/logos/' . $job->logo) : asset('img\com-logo-1.jpg') }} alt="Logo" class="mb-3 mx-auto" width="60">
                                <div class="text-start ps-4">
                                    <h5 class="mb-3">{{ $job->title }}</h5>
                                    <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i> {{ $job->company }}</span>
                                    <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>
                                        @if(mb_strlen($job->category) > 15)
                                            {{ mb_substr($job->category, 0, 15) . '...' }}
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
                                    <form method="POST" action="{{ route('job.favorite.toggle', $job->id) }}">
                                    @csrf
                                        <button type="submit" class="btn btn-light btn-square me-3">
                                            @if($user && $user->favorites && $user->favorites->contains($job->id))
                                                <i class="far fa-solid fa-heart text-primary"></i>
                                            @else
                                                <i class="far fa-heart text-primary" ></i>
                                            @endif
                                        </button>
                                    </form>
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
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $jobs->links() }}
    </div>
</div>
<!-- Jobs End -->
@endsection
