<!-- Spinner Start -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<!-- Spinner End -->

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="{{ route('welcome') }}" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
        <img src="{{ asset('img/logosikarir.png') }}" alt="SIKARIR Logo" style="height: 75px; margin-right: 0px;">
        <h1 class="m-0 text-primary">SIKARIR</h1>
    </a>
    <button type="button" class="navbar-toggler mr-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="{{ route('welcome') }}" class="nav-item nav-link @if(request()->routeIs('welcome')) active @endif">Home</a>
            <a href="{{ route('jobs') }}" class="nav-item nav-link @if(request()->routeIs('jobs')) active @endif">Jobs</a>
            <a href="{{ route('welcome') }}#mitra" class="nav-item nav-link">Mitra</a>

            @auth('user_accounts')
                <div class="nav-item dropdown d-lg-none">
                    <a class="nav-link dropdown-toggle" href="#" id="mobileUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth('user_accounts')->user()->name }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="mobileUserDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                        <li>
                            @if (Route::has('logout'))
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            @endif
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-item nav-link d-lg-none">Login</a>
            @endauth
        </div>
        @auth('user_accounts')
            <div class="nav-item dropdown d-none d-lg-block">
                <a class="btn btn-primary rounded-0 py-4 px-lg-5 dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ auth('user_accounts')->user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                    <li>
                        @if (Route::has('logout'))
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        @endif
                    </li>
                </ul>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">Login<i class="fa fa-arrow-right ms-3"></i></a>
        @endauth
    </div>
</nav>
<!-- Navbar End -->
