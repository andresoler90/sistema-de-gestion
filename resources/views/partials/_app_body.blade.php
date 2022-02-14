<!-- Wrapper Start -->
<div class="wrapper">

    @include('partials._body_left_sidebar')
    @include('partials._app_header')
    @yield('content')

</div>
<!-- Wrapper END -->
<!-- Footer -->
@include('partials._body_footer')
<!-- Footer END -->
