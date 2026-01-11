@extends('auth.layouts.auth-view')

@section('content-auth')
<h2 class="auth-title">Create Account</h2>
<p class="auth-subtitle">Join SIKARIR to find your dream internship</p>

{{-- Alert sukses --}}
@if(session('success'))
    <div class="alert alert-success mb-3" role="alert">
        <i class="fa fa-check-circle me-2" aria-hidden="true"></i>{{ session('success') }}
    </div>
@endif

{{-- Alert error --}}
@if($errors->any())
    <div class="alert alert-danger mb-3" role="alert">
        <ul class="mb-0 list-unstyled">
            @foreach($errors->all() as $error)
                <li><i class="fa fa-exclamation-circle me-2" aria-hidden="true"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
    @csrf

    {{-- Full Name --}}
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text"
            class="form-control @error('name') is-invalid @enderror"
            id="name"
            name="name"
            value="{{ old('name') }}"
            required
            autofocus
            autocomplete="name"
            placeholder="e.g. John Doe">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email"
            class="form-control @error('email') is-invalid @enderror"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autocomplete="email"
            placeholder="npm@student.unsika.ac.id"
            pattern="^[a-zA-Z0-9._%+-]+@student\.unsika\.ac\.id$"
            aria-describedby="emailHelp">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text" id="emailHelp">
            <i class="fa fa-info-circle me-1" aria-hidden="true"></i>Use your university email ending with <strong>@student.unsika.ac.id</strong>
        </div>
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password"
            class="form-control @error('password') is-invalid @enderror"
            id="password"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Create a strong password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Confirm Password --}}
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password"
            class="form-control"
            id="password_confirmation"
            name="password_confirmation"
            required
            autocomplete="new-password"
            placeholder="Repeat your password">
    </div>

    {{-- Submit --}}
    <div class="d-grid gap-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fa fa-user-plus me-2" aria-hidden="true"></i>Create Account
        </button>
        <p class="text-center text-muted mb-0">
            Already have an account? <a href="{{ route('login') }}" class="btn-link">Sign in here</a>
        </p>
    </div>
</form>

{{-- Validasi tambahan di sisi client --}}
@push('js')
<script>
document.getElementById('registerForm').addEventListener('submit', function (e) {
    const emailField = document.getElementById('email');
    const email = emailField.value.trim();

    if (!email.endsWith('@student.unsika.ac.id')) {
        e.preventDefault();
        alert('Gunakan email dengan domain @student.unsika.ac.id');
        emailField.focus();
    }
});
</script>
@endpush
@endsection