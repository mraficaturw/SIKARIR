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
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <h4>Dream Job (Favorit):</h4>
        @if($favorites->isEmpty())
            <p>You have no favorite jobs.</p>
        @else
            <ul class="list-group">
                @foreach($favorites as $job)
                    <li class="list-group-item">
                        <a href="{{ route('job.detail', $job->id) }}">{{ $job->title }} at {{ $job->company }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mb-4">
        <h4>Applied Jobs:</h4>
        @if($applied->isEmpty())
            <p>You have not marked any jobs as applied.</p>
        @else
            <ul class="list-group">
                @foreach($applied as $job)
                    <li class="list-group-item">
                        <a href="{{ route('job.detail', $job->id) }}">{{ $job->title }} at {{ $job->company }}</a>
                        <span class="text-muted">Applied at: {{ $job->pivot->applied_at->format('d M, Y') }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mb-4">
        <h4>Change Password:</h4>
        <a href="{{ route('profile.change-password') }}" class="btn btn-warning">Change Password</a>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@endsection
