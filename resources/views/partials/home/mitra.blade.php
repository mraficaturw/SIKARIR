<!-- Mitra Section -->
<section id="mitra" class="section-modern" style="background: var(--gray-100);">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6" data-animate>
                        <img class="img-fluid rounded-modern-lg" src="{{ asset('img/mitra-1.png') }}" alt="Mitra SIKARIR - Perusahaan 1" loading="lazy" width="300" height="200" style="box-shadow: var(--shadow-lg); object-fit: cover;">
                    </div>
                    <div class="col-6" data-animate>
                        <img class="img-fluid rounded-modern-lg mt-4" src="{{ asset('img/mitra-2.jpg') }}" alt="Mitra SIKARIR - Perusahaan 2" loading="lazy" width="300" height="200" style="box-shadow: var(--shadow-lg); object-fit: cover;">
                    </div>
                    <div class="col-6" data-animate>
                        <img class="img-fluid rounded-modern-lg" src="{{ asset('img/mitra-3.jpg') }}" alt="Mitra SIKARIR - Perusahaan 3" loading="lazy" width="300" height="200" style="box-shadow: var(--shadow-lg); object-fit: cover;">
                    </div>
                    <div class="col-6" data-animate>
                        <img class="img-fluid rounded-modern-lg mt-4" src="{{ asset('img/mitra-4.jpg') }}" alt="Mitra SIKARIR - Perusahaan 4" loading="lazy" width="300" height="200" style="box-shadow: var(--shadow-lg); object-fit: cover;">
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-animate>
                <h2 class="section-title mb-4">Jadilah Mitra Magang Kami</h2>
                <p class="text-muted mb-4">
                    SIKARIR Unsika membuka kesempatan bagi institusi dan perusahaan yang ingin menjadi mitra magang. 
                    Mitra magang diharapkan dapat memberikan pengalaman kerja yang bermanfaat serta membimbing mahasiswa.
                </p>
                
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-wrapper" style="width: 36px; height: 36px; min-width: 36px; border-radius: 10px; background: rgba(0, 176, 116, 0.1); display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                            <i class="fa fa-check text-success" style="font-size: 0.875rem;"></i>
                        </div>
                        <span>Memiliki badan usaha atau institusi yang sah dan legal</span>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-wrapper" style="width: 36px; height: 36px; min-width: 36px; border-radius: 10px; background: rgba(0, 176, 116, 0.1); display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                            <i class="fa fa-check text-success" style="font-size: 0.875rem;"></i>
                        </div>
                        <span>Menyediakan program magang yang jelas dan bermanfaat</span>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-wrapper" style="width: 36px; height: 36px; min-width: 36px; border-radius: 10px; background: rgba(0, 176, 116, 0.1); display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                            <i class="fa fa-check text-success" style="font-size: 0.875rem;"></i>
                        </div>
                        <span>Bersedia membimbing mahasiswa selama masa magang</span>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-wrapper" style="width: 36px; height: 36px; min-width: 36px; border-radius: 10px; background: rgba(0, 176, 116, 0.1); display: flex; align-items: center; justify-content: center;" aria-hidden="true">
                            <i class="fa fa-check text-success" style="font-size: 0.875rem;"></i>
                        </div>
                        <span>Menyediakan fasilitas dan lingkungan kerja yang aman</span>
                    </div>
                </div>
                
                <a class="btn-primary-modern" href="https://docs.google.com/forms/d/e/1FAIpQLSe_oyx4bBy63cBRnl-cezSLUZaFnItkwu3nWv-fHJRsWuitAw/viewform?usp=dialog" target="_blank" rel="noopener noreferrer">
                    <i class="fa fa-handshake me-2" aria-hidden="true"></i>Mari Berkolaborasi
                </a>
            </div>
        </div>
        
        @include('partials.home.logo-loop')
    </div>
</section>
