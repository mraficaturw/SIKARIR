{{--
    429 Too Many Requests
    Halaman ini ditampilkan ketika pengguna mengirim terlalu banyak request (rate limiting).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>429 - Terlalu Banyak Permintaan | SIKARIR</title>
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
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            
            <!-- Error Code -->
            <h1 class="error-code">429</h1>
            
            <!-- Error Title -->
            <h2 class="error-title">Terlalu Banyak Permintaan</h2>
            
            <!-- Error Description -->
            <p class="error-description">
                Anda telah mengirimkan terlalu banyak permintaan dalam waktu singkat.
                Silakan tunggu beberapa saat sebelum mencoba kembali.
            </p>
            
            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="javascript:location.reload()" class="btn-primary-error">
                    <i class="fa-solid fa-rotate-right"></i>
                    Coba Lagi
                </a>
                <a href="{{ url('/') }}" class="btn-secondary-error">
                    <i class="fa-solid fa-house"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Countdown Timer -->
            <div class="error-tips">
                <p><i class="fa-solid fa-lightbulb"></i> <strong>Tips:</strong> Tunggu 60 detik sebelum mencoba kembali.</p>
            </div>
        </div>

        <!-- Decorative Elements -->
        @include('errors.partials.decorations')
    </div>
</body>
</html>
