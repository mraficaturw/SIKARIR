{{--
    419 Page Expired
    Halaman ini ditampilkan ketika sesi pengguna telah kedaluwarsa,
    biasanya karena CSRF token tidak valid atau sesi telah habis.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Sesi Kedaluwarsa | SIKARIR</title>
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
            <div class="error-icon">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            
            <!-- Error Code -->
            <h1 class="error-code">419</h1>
            
            <!-- Error Title -->
            <h2 class="error-title">Sesi Kedaluwarsa</h2>
            
            <!-- Error Description -->
            <p class="error-description">
                Maaf, sesi Anda telah kedaluwarsa. Hal ini terjadi karena halaman tidak aktif terlalu lama 
                atau token keamanan sudah tidak valid. Silakan muat ulang halaman untuk melanjutkan.
            </p>
            
            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="javascript:location.reload()" class="btn-primary-error">
                    <i class="fa-solid fa-rotate-right"></i>
                    Muat Ulang Halaman
                </a>
                <a href="{{ url('/') }}" class="btn-secondary-error">
                    <i class="fa-solid fa-house"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Tips -->
            <div class="error-tips">
                <p><i class="fa-solid fa-lightbulb"></i> <strong>Tips:</strong> Pastikan cookies dan JavaScript aktif di browser Anda.</p>
            </div>
        </div>

        <!-- Decorative Elements -->
        @include('errors.partials.decorations')
    </div>
</body>
</html>
