@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <h2>Verifikasi Email Kamu</h2>
    <p>Silakan cek email <strong>{{ auth('user_accounts')->user()->email }}</strong> untuk link verifikasi.</p>
    <p>Belum menerima email?</p>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Kirim Ulang Email Verifikasi</button>
    </form>
</div>
@endsection
