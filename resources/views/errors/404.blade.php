{{--
    404 Not Found
    Halaman ini ditampilkan ketika URL yang diminta tidak ditemukan.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | SIKARIR</title>
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
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            
            <!-- Error Code -->
            <h1 class="error-code">404</h1>
            
            <!-- Error Title -->
            <h2 class="error-title">Halaman Tidak Ditemukan</h2>
            
            <!-- Error Description -->
            <p class="error-description">
                Oops! Halaman yang Anda cari tidak dapat ditemukan. 
                Mungkin halaman telah dipindahkan, dihapus, atau URL yang Anda masukkan salah.
            </p>
            
            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn-primary-error">
                    <i class="fa-solid fa-house"></i>
                    Kembali ke Beranda
                </a>
                <a href="javascript:history.back()" class="btn-secondary-error">
                    <i class="fa-solid fa-arrow-left"></i>
                    Halaman Sebelumnya
                </a>
            </div>

            <!-- Tips -->
            <div class="error-tips">
                <p><i class="fa-solid fa-lightbulb"></i> <strong>Tips:</strong> Periksa kembali URL yang Anda ketik atau gunakan menu navigasi.</p>
            </div>
        </div>

        <!-- Decorative Elements -->
        @include('errors.partials.decorations')
    </div>
</body>
</html>
