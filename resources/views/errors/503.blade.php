{{--
    503 Service Unavailable
    Halaman ini ditampilkan ketika aplikasi dalam mode maintenance.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Sedang Maintenance | SIKARIR</title>
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
            <div class="error-icon error-icon-info">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
            
            <!-- Error Code -->
            <h1 class="error-code">503</h1>
            
            <!-- Error Title -->
            <h2 class="error-title">Sedang Dalam Pemeliharaan</h2>
            
            <!-- Error Description -->
            <p class="error-description">
                Kami sedang melakukan pemeliharaan terjadwal untuk meningkatkan layanan kami.
                Silakan kembali dalam beberapa saat. Terima kasih atas kesabaran Anda!
            </p>
            
            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="javascript:location.reload()" class="btn-primary-error">
                    <i class="fa-solid fa-rotate-right"></i>
                    Muat Ulang
                </a>
            </div>

            <!-- Progress Indicator -->
            <div class="maintenance-progress">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <p>Kami akan segera kembali...</p>
            </div>
        </div>

        <!-- Decorative Elements -->
        @include('errors.partials.decorations')
    </div>
</body>
</html>
