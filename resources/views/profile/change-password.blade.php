@extends('auth.layouts.auth-view')

@section('content-auth')
<h2 class="auth-title">Change Password</h2>
<p class="auth-subtitle">Update your account password</p>

@if(session('success'))
    <div class="alert alert-success mb-3" role="alert">
        <i class="fa fa-check-circle me-2" aria-hidden="true"></i>{{ session('success') }}
    </div>
@endif
@if(session('status'))
    <div class="alert alert-info mb-3" role="alert">
        <i class="fa fa-info-circle me-2" aria-hidden="true"></i>{{ session('status') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger mb-3" role="alert">
        <ul class="mb-0 list-unstyled">
            @foreach($errors->all() as $error)
                <li><i class="fa fa-exclamation-circle me-2" aria-hidden="true"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('profile.change-password.post') }}">
    @csrf
    
    <div class="mb-3">
        <label for="current_password" class="form-label">Current Password</label>
        <input type="password" 
            class="form-control" 
            id="current_password" 
            name="current_password" 
            required 
            autofocus
            autocomplete="current-password"
            placeholder="Enter your current password">
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" 
            class="form-control" 
            id="password" 
            name="password" 
            required
            autocomplete="new-password"
            placeholder="Enter your new password">
    </div>
    
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirm New Password</label>
        <input type="password" 
            class="form-control" 
            id="password_confirmation" 
            name="password_confirmation" 
            required
            autocomplete="new-password"
            placeholder="Repeat your new password">
    </div>
    
    <div class="d-grid gap-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fa fa-key me-2" aria-hidden="true"></i>Update Password
        </button>
        <a href="{{ route('profile.show') }}" class="btn btn-secondary-modern text-center">
            <i class="fa fa-arrow-left me-2" aria-hidden="true"></i>Back to Profile
        </a>
    </div>
</form>
@endsection
