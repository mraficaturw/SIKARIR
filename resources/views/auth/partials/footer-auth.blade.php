<!-- Footer Start -->
@if (request()->routeIs('login' ) || request()->routeIs('profile.change-password'))
<div class="container-fluid bg-dark text-white-50 footer pt-5 pb-0 wow fadeIn fixed-bottom" data-wow-delay="0.1s">
@else
<div class="container-fluid bg-dark text-white-50 footer pt-5 pb-0 mt-0 wow fadeIn" data-wow-delay="0.1s">
@endif
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                &copy; <a class="border-bottom" href="#">SIKARIR</a> All Right Reserved.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="footer-menu">
                    <a href="{{ route('welcome') }}">Home</a>
                    <a href="{{ route('jobs') }}">Jobs</a>
                    <a href="">Cookies</a>
                    <a href="">Help</a>
                    <a href="">FQAs</a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
        </div>
    </div>
</div>
<!-- Footer End -->
