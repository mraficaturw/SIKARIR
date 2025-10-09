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
        </div>
        <a href="" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">Login<i class="fa fa-arrow-right ms-3"></i></a>
    </div>
</nav>
<!-- Navbar End -->
