// ========================================
// SIKARIR Modern SPA JavaScript
// Optimized for Performance with Turbo Drive
// ========================================

// Prevent re-initialization on Turbo navigations
if (window.SIKARIR_INITIALIZED) {
    // Already initialized, just run initApp for new content
    if (typeof initApp === 'function') initApp();
} else {
    window.SIKARIR_INITIALIZED = true;

    // Use requestIdleCallback for non-critical initialization
    window.scheduleTask = window.requestIdleCallback || ((cb) => setTimeout(cb, 1));

    // Initialize on first page load
    document.addEventListener('DOMContentLoaded', function () {
        initApp();
    });

    // Re-initialize after Turbo navigations
    document.addEventListener('turbo:load', function () {
        initApp();
    });

    // Reinitialize Alpine components after Turbo render
    document.addEventListener('turbo:render', function () {
        // Trigger Alpine to reinitialize on new content
        if (window.Alpine) {
            document.querySelectorAll('[x-data]').forEach(el => {
                if (!el._x_dataStack) {
                    window.Alpine.initTree(el);
                }
            });
        }
    });
}

function initApp() {
    // Initialize critical features immediately
    initNavbar();
    initBackToTop();

    // Defer non-critical animations
    window.scheduleTask(() => {
        initScrollAnimations();
    });
}

// ========================================
// Scroll Animations - Performance Optimized
// ========================================
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('[data-animate]');

    if (!animatedElements.length) return;

    // Check for reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        // Skip animations for users who prefer reduced motion
        animatedElements.forEach(el => el.classList.add('animated'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -30px 0px'
    });

    animatedElements.forEach(el => observer.observe(el));
}

// ========================================
// Modern Navbar Behavior - Optimized
// ========================================
function initNavbar() {
    const navbar = document.querySelector('.navbar-modern');
    if (!navbar) return;

    let lastScrollY = window.scrollY;
    let ticking = false;

    // Use passive event listener for better scroll performance
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                handleNavbarScroll(navbar, lastScrollY);
                lastScrollY = window.scrollY;
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });

    // Mobile menu toggle
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const menuOverlay = document.querySelector('.mobile-menu-overlay');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            menuOverlay?.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });

        menuOverlay?.addEventListener('click', () => {
            navMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });

        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                menuOverlay?.classList.remove('active');
                document.body.style.overflow = '';
                menuToggle.focus();
            }
        });
    }
}

function handleNavbarScroll(navbar, lastScrollY) {
    const currentScrollY = window.scrollY;

    // Add scrolled class
    if (currentScrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }

    // Hide/show on scroll direction (only after scrolling 200px)
    if (currentScrollY > 200) {
        if (currentScrollY > lastScrollY && currentScrollY > 100) {
            navbar.classList.add('hidden');
        } else {
            navbar.classList.remove('hidden');
        }
    } else {
        navbar.classList.remove('hidden');
    }
}

// ========================================
// Back to Top Button - Optimized
// ========================================
function initBackToTop() {
    const backToTop = document.querySelector('.back-to-top');
    if (!backToTop) return;

    // Use passive listener
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    }, { passive: true });

    backToTop.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// ========================================
// Alpine.js Data Components
// ========================================

// Jobs Search Component
function jobsSearch() {
    return {
        search: '',
        category: '',
        jobs: [],
        loading: false,
        page: 1,
        hasMore: true,

        init() {
            // Get initial values from URL params
            const params = new URLSearchParams(window.location.search);
            this.search = params.get('search') || '';
            this.category = params.get('category') || '';
        },

        handleSearch() {
            // Update URL and submit form traditionally for better SEO
            const url = new URL(window.location.href.split('?')[0]);
            if (this.search) url.searchParams.set('search', this.search);
            if (this.category) url.searchParams.set('category', this.category);
            window.location.href = url.toString();
        }
    };
}

// Favorite Toggle Component
function favoriteToggle(jobId, isFavorite) {
    return {
        isFavorite: isFavorite,
        loading: false,

        async toggle() {
            if (this.loading) return;

            this.loading = true;
            const previousState = this.isFavorite;

            // Optimistic update
            this.isFavorite = !this.isFavorite;

            try {
                const response = await fetch(`/jobs/${jobId}/favorite-toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    // Revert on error
                    this.isFavorite = previousState;
                    throw new Error('Failed to toggle favorite');
                }

            } catch (error) {
                console.error('Error toggling favorite:', error);
                this.isFavorite = previousState;
            } finally {
                this.loading = false;
            }
        }
    };
}

// Apply Toggle Component
function applyToggle(jobId, isApplied) {
    return {
        isApplied: isApplied,
        loading: false,

        async toggle() {
            if (this.loading) return;

            this.loading = true;
            const previousState = this.isApplied;

            // Optimistic update
            this.isApplied = !this.isApplied;

            try {
                const response = await fetch(`/jobs/${jobId}/applied-toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    this.isApplied = previousState;
                    throw new Error('Failed to toggle applied');
                }

            } catch (error) {
                console.error('Error toggling applied:', error);
                this.isApplied = previousState;
            } finally {
                this.loading = false;
            }
        }
    };
}

// Smooth scroll for anchor links - with passive listener
document.addEventListener('click', function (e) {
    const link = e.target.closest('a[href^="#"]');
    if (link) {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
});
