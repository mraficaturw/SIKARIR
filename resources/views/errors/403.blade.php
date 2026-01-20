{{--
    403 Forbidden
    Halaman ini ditampilkan ketika pengguna tidak memiliki izin untuk mengakses resource.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak | SIKARIR</title>
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @include('errors.partials.styles')
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <!-- Animated Icon -->
            <div class="error-icon error-icon-warning">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            
            <!-- Error Code -->
            <h1 class="error-code">403</h1>
            
            <!-- Error Title -->
            <h2 class="error-title">Akses Ditolak</h2>
            
            <!-- Error Description -->
            <p class="error-description">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. 
                Silakan login dengan akun yang memiliki hak akses yang sesuai.
            </p>
            
            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn-primary-error">
                    <i class="fa-solid fa-house"></i>
                    Kembali ke Beranda
                </a>
                @guest
                <a href="{{ route('login') }}" class="btn-secondary-error">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                </a>
                @endguest
            </div>

            <!-- Tips -->
            <div class="error-tips">
                <p><i class="fa-solid fa-lightbulb"></i> <strong>Tips:</strong> Pastikan Anda sudah login dengan akun yang benar.</p>
            </div>
        </div>

        <!-- Decorative Elements -->
        @include('errors.partials.decorations')
    </div>
</body>
</html>
