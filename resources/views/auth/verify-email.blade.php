@extends('auth.layouts.auth-view')

@section('content-auth')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
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

                            <a href="{{ route('logout') }}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Kembali</a>
                            @if (Route::has('logout'))
                                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                                    @csrf
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
