<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SIKARIR - Platform untuk mencari peluang magang terbaik bagi Mahasiswa Unsika">
    <meta name="theme-color" content="#00B074">

    <title>SIKARIR - Sistem Informasi Karir</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- Preload Critical Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    
    <!-- Google Fonts - Optimized with display swap -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome - Preload -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Modern CSS (Design System) -->
    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">

    <!-- Livewire Styles (includes Alpine.js) -->
    @livewireStyles
</head>
<body class="bg-white" x-data="{ mobileMenuOpen: false }">

    <!-- Skip Link for Accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Modern Navbar -->
    @include('partials.navbar-modern')

    <!-- Page Content -->
    <main id="main-content" class="page-transition" role="main">
        @yield('content')
    </main>

    <!-- Modern Footer -->
    @include('partials.footer-modern')

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top btn-primary-modern" style="position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 999;" aria-label="Back to top">
        <i class="fa fa-arrow-up" aria-hidden="true"></i>
    </a>

    <!-- Bootstrap JS - Defer -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modern App JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    @stack('js')
</body>
</html>
