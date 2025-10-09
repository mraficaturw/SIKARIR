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
                    <img class="flex-shrink-0 img-fluid border rounded" src="{{ $job->logo ? asset('storage/' . $job->logo) : asset('img/com-logo-2.jpg') }}" alt="" style="width: 80px; height: 80px;">
                    <div class="text-start ps-4">
                        <h3 class="mb-3">{{ $job->title }}</h3>
                        <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $job->location }}</span>
                        <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>Internship</span>
                        <span class="text-truncate me-0"><i class="far fa-money-bill-alt text-primary me-2"></i>Rp {{ number_format($job->salary_min) }} - Rp {{ number_format($job->salary_max) }}</span>
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

                <div class="">
                    <h4 class="mb-4">Apply For The Job</h4>
                    @if($job->apply_url)
                        <div class="row g-3">
                            <div class="col-12">
                                <a href="{{ $job->apply_url }}" target="_blank" class="btn btn-primary w-100">Apply Now</a>
                            </div>
                        </div>
                    @endif
                    <div class="row g-3 mt-3">
                        <div class="col-12">
                            <button class="btn btn-success w-100" type="button">Mark as Applied</button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start align-items-center mt-4 gap-3">
                        <a class="btn btn-outline-primary" href="{{ route('jobs') }}">Back to Jobs</a>
                        <a class="btn btn-light btn-square" href=""><i class="far fa-heart text-primary"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Job Detail End -->
@endsection
