@extends('auth.layouts.auth-view')

@section('content-auth')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center text-white">Register</h2>

            {{-- Alert sukses --}}
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
                        placeholder="Contoh : John Doe"
                        autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="Contoh : NPM@student.unsika.ac.id"
                        pattern="^[a-zA-Z0-9._%+-]+@student\.unsika\.ac\.id$"
                        title="Gunakan Email Kampus Unsika.">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Gunakan email kampus berakhiran <strong>@student.unsika.ac.id</strong>.
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        placeholder="Gunakan Sandi Yang Kuat"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password"
                        class="form-control"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ketik Ulang Sandi Kamu"
                        required>
                </div>

                {{-- Submit --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Register</button>
                    <a href="{{ route('login') }}" class="btn btn-link text-center text-white">Sudah punya akun? Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Validasi tambahan di sisi client --}}
<script>
document.getElementById('registerForm').addEventListener('submit', function (e) {
    const emailField = document.getElementById('email');
    const email = emailField.value.trim();

    if (!email.endsWith('@student.unsika.ac.id')) {
        e.preventDefault();
        alert('Gunakan email dengan domain @student.unsika.ac.id.');
        emailField.focus();
    }
});
</script>
@endsection