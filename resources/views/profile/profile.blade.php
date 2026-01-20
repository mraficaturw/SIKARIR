@extends('layouts.app')

@section('content')
<!-- Profile Header -->
<section style="background: var(--gradient-hero); padding: 3rem 0;">
    <div class="container">
        <div class="d-flex align-items-center gap-4 flex-wrap" data-animate>
            <a href="{{ route('profile.edit') }}" class="position-relative" title="Edit Profil" style="text-decoration: none;">
                <div class="rounded-circle bg-white p-1" style="width: 90px; height: 90px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                        <i class="fa fa-user text-primary" style="font-size: 2.5rem;" aria-hidden="true"></i>
                    @endif
                </div>
                <div class="position-absolute" style="bottom: 0; right: 0; background: var(--primary); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white;">
                    <i class="fa fa-pencil text-white" style="font-size: 0.75rem;"></i>
                </div>
            </a>
            <div>
                <h1 class="text-white fw-bold mb-1">Hi, {{ $user->name }}!</h1>
                <p class="text-white-50 mb-0"><i class="fa fa-envelope me-2" aria-hidden="true"></i>{{ $user->email }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="section-modern" style="padding-top: 2rem;">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success mb-4" role="alert">
                <i class="fa fa-check-circle me-2" aria-hidden="true"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('status'))
            <div class="alert alert-info mb-4" role="alert">
                <i class="fa fa-info-circle me-2" aria-hidden="true"></i>{{ session('status') }}
            </div>
        @endif

        <!-- Dream Jobs Section -->
        <div class="mb-5">
            <div class="d-flex align-items-center gap-3 mb-4" data-animate>
                <div class="icon-wrapper" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-heart text-danger" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-0">Dream Jobs (Favorites)</h3>
                    <p class="text-muted small mb-0">Jobs you've saved for later</p>
                </div>
            </div>
            
            @if($favorites->isEmpty())
                <div class="card-modern text-center py-5" data-animate>
                    <i class="fa fa-heart text-muted mb-3" style="font-size: 3rem; opacity: 0.3;" aria-hidden="true"></i>
                    <p class="text-muted mb-3">You haven't saved any jobs yet</p>
                    <a href="{{ route('jobs') }}" class="btn-primary-modern">
                        <i class="fa fa-search me-2" aria-hidden="true"></i>Explore Jobs
                    </a>
                </div>
            @else
                @if($favorites->count() > 2)
                    {{-- Carousel Mode --}}
                    <div class="jobs-carousel-container" data-animate>
                        <button class="carousel-nav-btn carousel-prev" onclick="scrollCarousel('favorites', -1)" aria-label="Previous">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <div class="jobs-carousel-wrapper" id="favorites-wrapper">
                            <div class="jobs-carousel" id="favorites-carousel">
                                @foreach($favorites as $job)
                                    <div class="carousel-item-job">
                                        @include('profile.partials.job-card-profile', ['job' => $job, 'showAppliedDate' => false])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button class="carousel-nav-btn carousel-next" onclick="scrollCarousel('favorites', 1)" aria-label="Next">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                @else
                    {{-- Grid Mode --}}
                    <div class="row g-4">
                        @foreach($favorites as $job)
                            <div class="col-md-6" data-animate>
                                @include('profile.partials.job-card-profile', ['job' => $job, 'showAppliedDate' => false])
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        <!-- Applied Jobs Section -->
        <div class="mb-5">
            <div class="d-flex align-items-center gap-3 mb-4" data-animate>
                <div class="icon-wrapper" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-paper-plane text-success" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-0">Applied Jobs</h3>
                    <p class="text-muted small mb-0">Track your job applications</p>
                </div>
            </div>
            
            @if($appliedJobs->isEmpty())
                <div class="card-modern text-center py-5" data-animate>
                    <i class="fa fa-paper-plane text-muted mb-3" style="font-size: 3rem; opacity: 0.3;" aria-hidden="true"></i>
                    <p class="text-muted mb-3">You haven't applied to any jobs yet</p>
                    <a href="{{ route('jobs') }}" class="btn-primary-modern">
                        <i class="fa fa-search me-2" aria-hidden="true"></i>Find Jobs
                    </a>
                </div>
            @else
                @if($appliedJobs->count() > 2)
                    {{-- Carousel Mode --}}
                    <div class="jobs-carousel-container" data-animate>
                        <button class="carousel-nav-btn carousel-prev" onclick="scrollCarousel('applied', -1)" aria-label="Previous">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <div class="jobs-carousel-wrapper" id="applied-wrapper">
                            <div class="jobs-carousel" id="applied-carousel">
                                @foreach($appliedJobs as $job)
                                    <div class="carousel-item-job">
                                        @include('profile.partials.job-card-profile', ['job' => $job, 'showAppliedDate' => true])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button class="carousel-nav-btn carousel-next" onclick="scrollCarousel('applied', 1)" aria-label="Next">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                @else
                    {{-- Grid Mode --}}
                    <div class="row g-4">
                        @foreach($appliedJobs as $job)
                            <div class="col-md-6" data-animate>
                                @include('profile.partials.job-card-profile', ['job' => $job, 'showAppliedDate' => true])
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        <!-- Account Settings - Premium Redesign -->
        <div class="account-settings-section" data-animate>
            <div class="settings-header-premium">
                <div class="settings-header-icon">
                    <i class="fa fa-sliders-h"></i>
                </div>
                <div class="settings-header-text">
                    <h3>Account Settings</h3>
                    <p>Kelola akun dan keamanan Anda</p>
                </div>
            </div>
            
            <div class="settings-cards-premium">
                <!-- Change Password Card -->
                <a href="{{ route('profile.change-password') }}" class="settings-card-premium settings-card-warning">
                    <div class="settings-card-glow"></div>
                    <div class="settings-card-content">
                        <div class="settings-card-icon">
                            <i class="fa fa-shield-alt"></i>
                        </div>
                        <div class="settings-card-body">
                            <h4>Ubah Password</h4>
                            <p>Perbarui kata sandi untuk keamanan akun</p>
                        </div>
                        <div class="settings-card-action">
                            <span class="settings-card-badge">Security</span>
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
                
                <!-- Logout Card -->
                <form method="POST" action="{{ route('logout') }}" class="settings-form-premium">
                    @csrf
                    <button type="submit" class="settings-card-premium settings-card-danger">
                        <div class="settings-card-glow"></div>
                        <div class="settings-card-content">
                            <div class="settings-card-icon">
                                <i class="fa fa-power-off"></i>
                            </div>
                            <div class="settings-card-body">
                                <h4>Keluar</h4>
                                <p>Logout dari akun Anda saat ini</p>
                            </div>
                            <div class="settings-card-action">
                                <span class="settings-card-badge">Exit</span>
                                <i class="fa fa-arrow-right"></i>
                            </div>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
    function scrollCarousel(type, direction) {
        const carousel = document.getElementById(type + '-carousel');
        if (!carousel) return;
        
        const itemWidth = carousel.querySelector('.carousel-item-job')?.offsetWidth || 350;
        const gap = 24; // 1.5rem gap
        const scrollAmount = (itemWidth + gap) * direction;
        
        carousel.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }

    // Update fade edges based on scroll position
    function updateCarouselFades(carousel, wrapper) {
        if (!carousel || !wrapper) return;
        
        const scrollLeft = carousel.scrollLeft;
        const scrollWidth = carousel.scrollWidth;
        const clientWidth = carousel.clientWidth;
        
        // Show left fade when scrolled
        if (scrollLeft > 10) {
            wrapper.classList.add('scrolled-left');
        } else {
            wrapper.classList.remove('scrolled-left');
        }
        
        // Hide right fade when scrolled to end
        if (scrollLeft + clientWidth >= scrollWidth - 10) {
            wrapper.classList.add('scrolled-end');
        } else {
            wrapper.classList.remove('scrolled-end');
        }
    }

    // Initialize carousel scroll listeners
    document.addEventListener('DOMContentLoaded', function() {
        const carousels = [
            { carousel: 'favorites-carousel', wrapper: 'favorites-wrapper' },
            { carousel: 'applied-carousel', wrapper: 'applied-wrapper' }
        ];
        
        carousels.forEach(function(item) {
            const carousel = document.getElementById(item.carousel);
            const wrapper = document.getElementById(item.wrapper);
            
            if (carousel && wrapper) {
                // Initial state
                updateCarouselFades(carousel, wrapper);
                
                // Listen for scroll
                carousel.addEventListener('scroll', function() {
                    updateCarouselFades(carousel, wrapper);
                });
            }
        });
    });
</script>
@endpush