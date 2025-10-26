@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Hi, {{ $user->name }}!</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    <div class="mt-5 mb-5">
        <h4 class="fw-semibold mb-4">Dream Jobs (Favorites):</h4>
        @if($favorites->isEmpty())
            <div class="bg-white rounded shadow-sm p-4 border text-center">
                <p class="text-muted mb-0">You have no favorite jobs yet.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($favorites as $job)
                    <div class="col-md-6">
                        <div class="bg-white rounded shadow-sm p-4 border h-100">
                            <h5 class="fw-semibold mb-2">{{ $job->title }}</h5>
                            <p class="text-muted mb-3"><i class="fa fa-building text-primary me-2"></i>{{ $job->company }} - {{ $job->location ?? 'Not specified' }}</p>
                            <a href="{{ route('job.detail', $job->id) }}" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mb-5">
        <h4 class="fw-semibold mb-4">Applied Jobs:</h4>
        @if($appliedJobs->isEmpty())
            <div class="bg-white rounded shadow-sm p-4 border text-center">
                <p class="text-muted mb-0">You have not applied to any jobs yet.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($appliedJobs as $job)
                    <div class="col-md-6">
                        <div class="bg-white rounded shadow-sm p-4 border h-100">
                            <h5 class="fw-semibold mb-2">{{ $job->title }}</h5>
                            <p class="text-muted mb-2"><i class="fa fa-building text-primary me-2"></i>{{ $job->company }} - {{ $job->location ?? 'Not specified' }}</p>
                            <p class="text-muted small mb-3">
                                <i class="fa fa-calendar text-primary me-1"></i>Applied: {{ \Carbon\Carbon::parse($job->pivot->applied_at)->format('d M, Y H:i') }}
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('job.detail', $job->id) }}" class="btn btn-primary btn-sm">View Details</a>
                                @if($job->apply_url)
                                    <a href="{{ $job->apply_url }}" target="_blank" class="btn btn-success btn-sm">Apply Now</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mb-4">
        <h4>Account Settings:</h4>
        <a href="{{ route('profile.change-password') }}" class="btn btn-warning">Change Password</a>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@endsection