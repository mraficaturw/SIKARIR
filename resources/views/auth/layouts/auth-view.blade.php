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

    <!-- Font Awesome - Preload -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <style>
        .auth-background {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.9) 100%),
                        url('{{ asset('img/background-auth.jpg') }}') center/cover no-repeat fixed;
            display: flex;
            flex-direction: column;
        }
        
        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .auth-card {
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            text-decoration: none;
        }
        
        .auth-logo img {
            height: 48px;
            width: auto;
        }
        
        .auth-logo h1 {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, #00B074 0%, #00D4AA 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        
        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0F172A;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .auth-subtitle {
            font-size: 0.9375rem;
            color: #64748B;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .auth-footer {
            text-align: center;
            padding: 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
        }
        
        .auth-footer a {
            color: #00D4AA;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .auth-footer a:hover {
            color: #00B074;
        }
        
        .form-label {
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 0.75rem !important;
            border: 1px solid #E2E8F0 !important;
            padding: 0.875rem 1rem !important;
            transition: all 0.2s !important;
        }
        
        .form-control:focus {
            border-color: #00B074 !important;
            box-shadow: 0 0 0 3px rgba(0, 176, 116, 0.15) !important;
        }
        
        .form-text {
            color: #64748B;
            font-size: 0.8125rem;
        }
        
        .btn-link {
            color: #00B074;
            text-decoration: none;
            font-weight: 500;
        }
        
        .btn-link:hover {
            color: #009960;
            text-decoration: underline;
        }
    </style>
</head>
<body class="auth-background">
    <!-- Skip Link -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Auth Container -->
    <div class="auth-container" id="main-content" role="main">
        <div class="auth-card page-transition">
            <!-- Logo -->
            <a href="{{ route('welcome') }}" class="auth-logo" aria-label="Go to homepage">
                <img src="{{ asset('img/logosikarir.png') }}" alt="SIKARIR Logo">
                <h1>SIKARIR</h1>
            </a>
            
            @yield('content-auth')
        </div>
    </div>

    <!-- Footer -->
    <footer class="auth-footer">
        <p>&copy; {{ date('Y') }} SIKARIR - <a href="{{ route('welcome') }}">Back to Home</a></p>
    </footer>

    <!-- Bootstrap JS - Defer -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('js')
</body>
</html>
