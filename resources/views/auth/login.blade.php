@extends('auth.layouts.auth-view')

@section('content-auth')
<div class="container py-5 pt-10">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center text-white">Login</h2>

            {{-- Alert sukses (misal setelah register) --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Alert error --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        pattern="^[a-zA-Z0-9._%+-]+@student\.unsika\.ac\.id$"
                        title="Gunakan email dengan domain @student.unsika.ac.id">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Gunakan Email Kampus Unsika.</strong>.
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="mb-3 form-check">
                    <input type="checkbox"
                        class="form-check-input"
                        id="remember"
                        name="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                {{-- Submit --}}
                <div class="d-grid gap-5">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="{{ route('register') }}" class="btn btn-link text-center text-white">Belum punya akun? Register</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Validasi tambahan di sisi client --}}
<script>
const form = document.getElementById('loginForm');
if (form) {
    form.addEventListener('submit', function (e) {
        const emailField = document.getElementById('email');
        const email = emailField.value.trim();

        if (!email.endsWith('@student.unsika.ac.id')) {
            e.preventDefault();
            alert('Gunakan Email Kampus Unsika.');
            emailField.focus();
        }
    });
}
</script>
@endsection