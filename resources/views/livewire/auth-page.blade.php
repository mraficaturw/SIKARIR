<div class="auth-split-container {{ $mode === 'register' ? 'register-mode' : 'login-mode' }}" 
     wire:key="auth-container-{{ $mode }}">
    
    {{-- Inline Responsive CSS to ensure mobile/tablet responsiveness --}}
    <style>
        /* Force responsive layout for tablet and mobile */
        @media (max-width: 992px) {
            .auth-split-container {
                display: flex !important;
                flex-direction: column !important;
                grid-template-columns: unset !important;
            }
            
            .auth-split-container .auth-welcome-panel {
                order: 1 !important;
                width: 100% !important;
                border-radius: 0 0 24px 24px !important;
                min-height: 200px !important;
            }
            
            .auth-split-container .auth-form-panel {
                order: 2 !important;
                width: 100% !important;
                flex: 1 !important;
            }
            
            .auth-split-container.register-mode .auth-welcome-panel {
                order: 1 !important;
            }
            
            .auth-split-container.register-mode .auth-form-panel {
                order: 2 !important;
            }
        }
        
        /* Tablet specific (577px - 992px) */
        @media (min-width: 577px) and (max-width: 992px) {
            .auth-split-container .auth-welcome-panel {
                padding: 2rem 1.5rem !important;
                min-height: 220px !important;
            }
            
            .auth-split-container .welcome-features {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
                justify-content: center !important;
                gap: 0.75rem !important;
            }
            
            .auth-split-container .welcome-text p {
                display: block !important;
            }
        }
        
        /* Mobile specific (< 576px) */
        @media (max-width: 576px) {
            .auth-split-container .auth-welcome-panel {
                padding: 1.25rem 1rem !important;
                min-height: 160px !important;
                border-radius: 0 0 16px 16px !important;
            }
            
            .auth-split-container .welcome-text p {
                display: none !important;
            }
            
            .auth-split-container .welcome-features {
                display: none !important;
            }
            
            .auth-split-container .welcome-text h2 {
                font-size: 1.375rem !important;
            }
            
            .auth-split-container .auth-split-logo img {
                height: 36px !important;
            }
            
            .auth-split-container .auth-split-logo h1 {
                font-size: 1.25rem !important;
            }
            
            .auth-split-container .auth-form-wrapper {
                padding: 1rem 0.75rem !important;
            }
            
            .auth-split-container .auth-title {
                font-size: 1.375rem !important;
            }
            
            .auth-split-container .auth-subtitle {
                font-size: 0.875rem !important;
            }
            
            .auth-split-container .floating-blob,
            .auth-split-container .floating-shape {
                display: none !important;
            }
        }
    </style>
    
    <!-- Welcome Panel -->
    <div class="auth-welcome-panel">
        <div class="welcome-content">
            <!-- Logo -->
            <a href="{{ route('welcome') }}" class="auth-split-logo">
                <img src="{{ asset('img/logosikarir.png') }}" alt="SIKARIR Logo">
                <h1>SIKARIR</h1>
            </a>
            
            <div class="welcome-text">
                @if($mode === 'login')
                    <h2>Selamat Datang Kembali!</h2>
                    <p>Masuk untuk melanjutkan perjalanan karirmu bersama SIKARIR.</p>
                @else
                    <h2>Bergabung Sekarang!</h2>
                    <p>Daftarkan dirimu dan raih kesempatan magang terbaik.</p>
                @endif
                
                <div class="welcome-features">
                    <div class="welcome-feature">
                        <i class="fa fa-handshake"></i>
                        <span>Perusahaan Mitra Resmi Unsika</span>
                    </div>
                    <div class="welcome-feature">
                        <i class="fa fa-award"></i>
                        <span>Jaminan Konversi SKS</span>
                    </div>
                    <div class="welcome-feature">
                        <i class="fa fa-briefcase"></i>
                        <span>Cari Sesuai Fakultas</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Floating Decorative Shapes - Full Panel -->
        <div class="welcome-decoration-full">
            <div class="floating-blob blob-1"></div>
            <div class="floating-blob blob-2"></div>
            <div class="floating-blob blob-3"></div>
            <div class="floating-blob blob-4"></div>
            <div class="floating-blob blob-5"></div>
        </div>
    </div>
    
    <!-- Form Panel -->
    <div class="auth-form-panel">
        <div class="auth-form-wrapper">
            <!-- Mobile Logo -->
            <a href="{{ route('welcome') }}" class="auth-mobile-logo">
                <img src="{{ asset('img/logosikarir.png') }}" alt="SIKARIR Logo">
                <h1>SIKARIR</h1>
            </a>
            
            <!-- Form Content with Animation -->
            <div class="auth-form-content" wire:key="form-{{ $mode }}">
                @if($mode === 'login')
                    <!-- Login Form -->
                    <h2 class="auth-title">Welcome Back</h2>
                    <p class="auth-subtitle">Sign in to continue to SIKARIR</p>

                    @if(session('success'))
                        <div class="alert alert-success mb-3" role="alert">
                            <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-info mb-3" role="alert">
                            <i class="fa fa-info-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    <form wire:submit="login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                wire:model="email"
                                placeholder="npm@student.unsika.ac.id"
                                autocomplete="email"
                                autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fa fa-info-circle me-1"></i>Gunakan Email Kampus Unsika
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                wire:model="password"
                                placeholder="Enter your password"
                                autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox"
                                class="form-check-input"
                                id="remember"
                                wire:model="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="login">
                                    <i class="fa fa-sign-in-alt me-2"></i>Sign In
                                </span>
                                <span wire:loading wire:target="login">
                                    <i class="fa fa-spinner fa-spin me-2"></i>Signing in...
                                </span>
                            </button>
                            <p class="text-center text-muted mb-0">
                                Don't have an account? 
                                <button type="button" wire:click="switchMode('register')" class="btn-link-auth">
                                    Register here
                                </button>
                            </p>
                        </div>
                    </form>
                @else
                    <!-- Register Form -->
                    <h2 class="auth-title">Create Account</h2>
                    <p class="auth-subtitle">Join SIKARIR to find your dream internship</p>

                    <form wire:submit="register">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                wire:model="name"
                                placeholder="e.g. John Doe"
                                autocomplete="name"
                                autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reg-email" class="form-label">Email Address</label>
                            <input type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="reg-email"
                                wire:model="email"
                                placeholder="npm@student.unsika.ac.id"
                                autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fa fa-info-circle me-1"></i>Use your university email ending with <strong>@student.unsika.ac.id</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reg-password" class="form-label">Password</label>
                            <input type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="reg-password"
                                wire:model="password"
                                placeholder="Create a strong password"
                                autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password"
                                class="form-control"
                                id="password_confirmation"
                                wire:model="password_confirmation"
                                placeholder="Repeat your password"
                                autocomplete="new-password">
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="register">
                                    <i class="fa fa-user-plus me-2"></i>Create Account
                                </span>
                                <span wire:loading wire:target="register">
                                    <i class="fa fa-spinner fa-spin me-2"></i>Creating Account...
                                </span>
                            </button>
                            <p class="text-center text-muted mb-0">
                                Already have an account? 
                                <button type="button" wire:click="switchMode('login')" class="btn-link-auth">
                                    Sign in here
                                </button>
                            </p>
                        </div>
                    </form>
                @endif
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="auth-split-footer">
            <p>&copy; {{ date('Y') }} SIKARIR - <a href="{{ route('welcome') }}">Back to Home</a></p>
        </footer>
    </div>
</div>
