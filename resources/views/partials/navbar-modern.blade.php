<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" 
     :class="{ 'active': mobileMenuOpen }" 
     @click="mobileMenuOpen = false"
     aria-hidden="true"></div>

<!-- Modern Navbar -->
<nav class="navbar-modern" role="navigation" aria-label="Main navigation">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Brand -->
            <a href="{{ route('welcome') }}" class="navbar-brand" aria-label="SIKARIR - Halaman Utama" wire:navigate>
                <img src="{{ asset('img/logosikarir.png') }}" alt="" aria-hidden="true">
                <h1>SIKARIR</h1>
            </a>

            <!-- Desktop Navigation -->
            <div class="nav-menu d-none d-lg-flex align-items-center gap-1" role="menubar">
                <a href="{{ route('welcome') }}" 
                   class="nav-link @if(request()->routeIs('welcome')) active @endif"
                   role="menuitem"
                   wire:navigate
                   @if(request()->routeIs('welcome')) aria-current="page" @endif>Home</a>
                <a href="{{ route('jobs') }}" 
                   class="nav-link @if(request()->routeIs('jobs')) active @endif"
                   role="menuitem"
                   wire:navigate
                   @if(request()->routeIs('jobs')) aria-current="page" @endif>Jobs</a>
                <a href="{{ route('welcome') }}#mitra" 
                   class="nav-link"
                   role="menuitem">Mitra</a>
            </div>

            <!-- Desktop Auth -->
            <div class="d-none d-lg-flex align-items-center gap-3">
                @auth('user_accounts')
                    <div class="dropdown">
                        <button class="btn-primary-modern dropdown-toggle" 
                                type="button" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false"
                                aria-haspopup="true"
                                id="userMenuButton">
                            <i class="fa fa-user me-2" aria-hidden="true"></i>{{ auth('user_accounts')->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}" wire:navigate>
                                    <i class="fa fa-user me-2" aria-hidden="true"></i>Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa fa-sign-out-alt me-2" aria-hidden="true"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-primary-modern" wire:navigate>
                        Login <i class="fa fa-arrow-right ms-2" aria-hidden="true"></i>
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle d-lg-none" 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    :aria-expanded="mobileMenuOpen.toString()"
                    aria-controls="mobileNav"
                    aria-label="Toggle navigation menu">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="nav-menu d-lg-none" 
         :class="{ 'active': mobileMenuOpen }"
         id="mobileNav"
         role="menu"
         aria-label="Mobile navigation">
        <a href="{{ route('welcome') }}" 
           class="nav-link @if(request()->routeIs('welcome')) active @endif"
           role="menuitem"
           wire:navigate
           @if(request()->routeIs('welcome')) aria-current="page" @endif>Home</a>
        <a href="{{ route('jobs') }}" 
           class="nav-link @if(request()->routeIs('jobs')) active @endif"
           role="menuitem"
           wire:navigate
           @if(request()->routeIs('jobs')) aria-current="page" @endif>Jobs</a>
        <a href="{{ route('welcome') }}#mitra" 
           class="nav-link"
           role="menuitem">Mitra</a>
        
        <hr class="my-3">
        
        @auth('user_accounts')
            <a href="{{ route('profile.show') }}" class="nav-link" role="menuitem" wire:navigate>
                <i class="fa fa-user me-2" aria-hidden="true"></i>Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn-primary-modern w-100">
                    <i class="fa fa-sign-out-alt me-2" aria-hidden="true"></i>Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-primary-modern w-100 text-center mt-3" wire:navigate>
                Login <i class="fa fa-arrow-right ms-2" aria-hidden="true"></i>
            </a>
        @endauth
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div style="height: 72px;" aria-hidden="true"></div>
