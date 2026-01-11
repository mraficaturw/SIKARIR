@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section style="background: var(--gradient-hero); padding: 3rem 0;">
    <div class="container">
        <div class="text-center" data-animate>
            <h1 class="text-white fw-bold mb-2">Temukan Lowongan Magang</h1>
            <p class="text-white-50 mb-0">Cari lowongan yang sesuai dengan bidang studimu</p>
        </div>
    </div>
</section>

<livewire:job-search />
@endsection
