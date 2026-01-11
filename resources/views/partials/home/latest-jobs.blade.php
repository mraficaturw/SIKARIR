<!-- Latest Jobs Section -->
<section class="section-modern bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title" data-animate>Lowongan Terbaru</h2>
            <p class="section-subtitle" data-animate>Jangan lewatkan kesempatan magang terbaik untukmu</p>
        </div>
        
        <div class="row g-4">
            @foreach($jobs as $job)
                <div class="col-lg-6" data-animate>
                    @include('partials.components.job-card', ['job' => $job, 'user' => $user ?? null])
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5" data-animate>
            <a href="{{ route('jobs') }}" class="btn-primary-modern" wire:navigate>
                <i class="fa fa-briefcase me-2" aria-hidden="true"></i>Lihat Semua Lowongan
            </a>
        </div>
    </div>
</section>
