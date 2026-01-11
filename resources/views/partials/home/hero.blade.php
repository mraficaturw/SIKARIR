<!-- Hero Section -->
<section class="hero-modern">
    <!-- Floating Elements -->
    <div class="floating-element" aria-hidden="true"></div>
    <div class="floating-element" aria-hidden="true"></div>
    <div class="floating-element" aria-hidden="true"></div>
    
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <div class="hero-content" data-animate>
                    <h1 class="hero-title">
                        Temukan <span class="highlight">Peluang Magang</span> Terbaik untuk Karirmu
                    </h1>
                    <p class="hero-subtitle">
                        SIKARIR membantu Mahasiswa Unsika menemukan lowongan magang sesuai bidang peminatan dengan mudah dan cepat.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('jobs') }}" class="btn-primary-modern" wire:navigate>
                            <i class="fa fa-search me-2" aria-hidden="true"></i>Cari Lowongan
                        </a>
                        <a href="#categories" class="btn-outline-modern" style="border-color: rgba(255,255,255,0.3); color: white;">
                            <i class="fa-solid fa-layer-group me-2" aria-hidden="true"></i>Sesuaikan Jurusan
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-image-container" data-animate>
                    <img src="{{ asset('img/unsika.jpg') }}" 
                         alt="Kampus Universitas Singaperbangsa Karawang" 
                         class="img-fluid" 
                         style="max-height: 450px; width: 100%; object-fit: cover;"
                         loading="eager"
                         fetchpriority="high"
                         width="600"
                         height="450">
                </div>
            </div>
        </div>
    </div>
</section>
