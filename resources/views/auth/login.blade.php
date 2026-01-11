@extends('auth.layouts.auth-view')

@section('content-auth')
<h2 class="auth-title">Welcome Back</h2>
<p class="auth-subtitle">Sign in to continue to SIKARIR</p>

{{-- Alert sukses (misal setelah register) --}}
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

<form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
    @csrf

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email"
            class="form-control @error('email') is-invalid @enderror"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            autocomplete="email"
            placeholder="npm@student.unsika.ac.id"
            pattern="^[a-zA-Z0-9._%+-]+@student\.unsika\.ac\.id$"
            aria-describedby="emailHelp">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text" id="emailHelp">
            <i class="fa fa-info-circle me-1" aria-hidden="true"></i>Gunakan Email Kampus Unsika
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
            autocomplete="current-password"
            placeholder="Enter your password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Remember Me --}}
    <div class="mb-4 form-check">
        <input type="checkbox"
            class="form-check-input"
            id="remember"
            name="remember"
            {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">Remember me</label>
    </div>

    {{-- Submit --}}
    <div class="d-grid gap-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fa fa-sign-in-alt me-2" aria-hidden="true"></i>Sign In
        </button>
        <p class="text-center text-muted mb-0">
            Don't have an account? <a href="{{ route('register') }}" class="btn-link">Register here</a>
        </p>
    </div>
</form>

{{-- Validasi tambahan di sisi client --}}
@push('js')
<script>
const form = document.getElementById('loginForm');
if (form) {
    form.addEventListener('submit', function (e) {
        const emailField = document.getElementById('email');
        const email = emailField.value.trim();

        if (!email.endsWith('@student.unsika.ac.id')) {
            e.preventDefault();
            alert('Gunakan Email Kampus Unsika (@student.unsika.ac.id)');
            emailField.focus();
        }
    });
}
</script>
@endpush
@endsection