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
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Job List</h1>
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
                    <div class="d-flex justify-content-center mt-4">
                        {{ $jobs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Jobs End -->
@endsection
