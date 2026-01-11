<!-- Modern Footer -->
<footer class="footer-modern" role="contentinfo">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand">
                    <img src="{{ asset('img/logosikarir.png') }}" alt="" aria-hidden="true">
                    <span>SIKARIR</span>
                </div>
                <p class="mb-0" style="max-width: 300px;">
                    Platform terpusat untuk membantu Mahasiswa Unsika menemukan peluang magang terbaik sesuai bidang peminatan.
                </p>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <h6 class="text-white mb-3">Quick Links</h6>
                <nav class="footer-links d-flex flex-column gap-2" aria-label="Footer navigation">
                    <a href="{{ route('welcome') }}" wire:navigate>Home</a>
                    <a href="{{ route('jobs') }}" wire:navigate>Jobs</a>
                    <a href="{{ route('welcome') }}#mitra">Mitra</a>
                </nav>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <h6 class="text-white mb-3">Support</h6>
                <nav class="footer-links d-flex flex-column gap-2" aria-label="Support links">
                    <a href="#">FAQ</a>
                    <a href="#">Help Center</a>
                    <a href="#">Contact Us</a>
                </nav>
            </div>
            
            <div class="col-lg-4 col-md-4">
                <h6 class="text-white mb-3">Connect With Us</h6>
                <div class="d-flex gap-2 mb-3">
                    <a href="#" 
                       class="btn-ghost" 
                       style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px;"
                       aria-label="Follow us on Instagram">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                    </a>
                    <a href="#" 
                       class="btn-ghost" 
                       style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px;"
                       aria-label="Follow us on LinkedIn">
                        <i class="fab fa-linkedin" aria-hidden="true"></i>
                    </a>
                    <a href="#" 
                       class="btn-ghost" 
                       style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px;"
                       aria-label="Watch us on YouTube">
                        <i class="fab fa-youtube" aria-hidden="true"></i>
                    </a>
                </div>
                <p class="small mb-0">
                    <i class="fa fa-envelope me-2" aria-hidden="true"></i>
                    <a href="mailto:sikarir@unsika.ac.id" style="color: inherit; text-decoration: none;">sikarir@unsika.ac.id</a>
                </p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="mb-0">© {{ date('Y') }} <span class="text-gradient">SIKARIR</span>. All Rights Reserved.</p>
        </div>
    </div>
</footer>
