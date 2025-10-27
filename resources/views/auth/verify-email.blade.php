@extends('auth.layouts.auth-view')

@section('content-auth')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="w-80 px-3" style="max-width: 680px;">
        <div class="card shadow-sm">
            <div class="card-body text-center p-4">
                <h2 class="mb-3">Verifikasi Email Kamu</h2>
                <p>Silakan cek email <strong>{{ auth('user_accounts')->user()->email }}</strong> untuk link verifikasi.</p>
                <p class="mb-3">Belum menerima email?</p>

                @if (session('message'))
                    <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                @endif

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <form method="POST" action="{{ route('verification.send') }}" class="mb-2 mb-sm-0">
                        @csrf
                        <button type="submit" class="btn btn-primary">Kirim Ulang Email Verifikasi</button>
                    </form>

                    <a href="{{ route('logout') }}" class="btn btn-link">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
