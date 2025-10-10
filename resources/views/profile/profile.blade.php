@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Profile: {{ $user->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    <div class="mb-4">
        <h4>Dream Job (Favorit):</h4>
        @if($favorites->isEmpty())
            <p>You have no favorite jobs.</p>
        @else
            <div class="row">
                @foreach($favorites as $job)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $job->title }}</h5>
                                <p class="card-text">{{ $job->company }} - {{ $job->location }}</p>
                                <a href="{{ route('job.detail', $job->id) }}" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mb-4">
        <h4>Applied Jobs:</h4>
        @if($appliedJobs->isEmpty())
            <p>You have not marked any jobs as applied.</p>
        @else
            <div class="row">
                @foreach($appliedJobs as $job)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $job->title }}</h5>
                                <p class="card-text">{{ $job->company }} - {{ $job->location }}</p>
                                <p class="text-muted small">
                                    Applied: {{ \Carbon\Carbon::parse($job->pivot->applied_at)->format('d M, Y H:i') }}
                                </p>
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