@extends('auth.layouts.auth-view')

@section('content-auth')
<div class="text-center mb-4">
    <div class="mb-4">
        <div style="width: 80px; height: 80px; background: rgba(0, 176, 116, 0.1); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
            <i class="fa fa-envelope text-primary" style="font-size: 2rem;" aria-hidden="true"></i>
        </div>
    </div>
    <h2 class="auth-title">Verifikasi Email</h2>
    <p class="auth-subtitle">
        Silakan cek email <strong>{{ auth('user_accounts')->user()->email }}</strong> untuk link verifikasi.
    </p>
</div>

@if (session('message'))
    <div class="alert alert-success mb-4" role="alert">
        <i class="fa fa-check-circle me-2" aria-hidden="true"></i>{{ session('message') }}
    </div>
@endif

<div class="card-modern p-4 mb-4" style="background: var(--gray-100);">
    <div class="d-flex align-items-start gap-3">
        <i class="fa fa-info-circle text-primary mt-1" aria-hidden="true"></i>
        <div>
            <p class="mb-0 small text-muted">
                Belum menerima email verifikasi? Klik tombol di bawah untuk mengirim ulang.
            </p>
        </div>
    </div>
</div>

<div class="d-grid gap-3">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="fa fa-paper-plane me-2" aria-hidden="true"></i>Kirim Ulang Email Verifikasi
        </button>
    </form>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger w-100">
            <i class="fa fa-sign-out-alt me-2" aria-hidden="true"></i>Logout
        </button>
    </form>
</div>
@endsection
