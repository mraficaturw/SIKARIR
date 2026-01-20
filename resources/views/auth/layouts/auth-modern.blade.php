<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#00B074">

    <title>SIKARIR - {{ $title ?? 'Authentication' }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- Preload Critical Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Modern CSS -->
    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
</head>
<body class="auth-split-body">
    <!-- Skip Link -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Split Screen Container -->
    <div class="auth-split-container @yield('auth-mode', 'login-mode')" id="main-content" role="main">
        
        <!-- Welcome Panel -->
        <div class="auth-welcome-panel">
            <div class="welcome-content">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="auth-split-logo" aria-label="Go to homepage">
                    <img src="{{ asset('img/logosikarir.png') }}" alt="SIKARIR Logo">
                    <h1>SIKARIR</h1>
                </a>
                
                <div class="welcome-text">
                    <h2>@yield('welcome-title', 'Selamat Datang!')</h2>
                    <p>@yield('welcome-subtitle', 'Temukan peluang magang terbaik untuk karirmu bersama SIKARIR - Sistem Informasi Karir & Rekrutmen.')</p>
                    
                    <div class="welcome-features">
                        <div class="welcome-feature">
                            <i class="fa fa-briefcase"></i>
                            <span>1000+ Lowongan Magang</span>
                        </div>
                        <div class="welcome-feature">
                            <i class="fa fa-building"></i>
                            <span>100+ Perusahaan Partner</span>
                        </div>
                        <div class="welcome-feature">
                            <i class="fa fa-graduation-cap"></i>
                            <span>Khusus Mahasiswa Unsika</span>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Elements -->
                <div class="welcome-decoration">
                    <div class="floating-shape shape-1"></div>
                    <div class="floating-shape shape-2"></div>
                    <div class="floating-shape shape-3"></div>
                </div>
            </div>
        </div>
        
        <!-- Form Panel -->
        <div class="auth-form-panel">
            <div class="auth-form-wrapper">
                <!-- Mobile Logo (shown only on mobile) -->
                <a href="{{ route('welcome') }}" class="auth-mobile-logo" aria-label="Go to homepage">
                    <img src="{{ asset('img/logosikarir.png') }}" alt="SIKARIR Logo">
                    <h1>SIKARIR</h1>
                </a>
                
                @yield('content-auth')
            </div>
            
            <!-- Footer -->
            <footer class="auth-split-footer">
                <p>&copy; {{ date('Y') }} SIKARIR - <a href="{{ route('welcome') }}">Back to Home</a></p>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('js')
</body>
</html>
