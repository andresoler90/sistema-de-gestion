<!doctype html>
<html lang="es">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"/>
    <!-- Typography CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/typography.css') }}"/>
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"/>
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}"/>
    <!-- Full calendar -->
    <link href="{{ asset('assets/fullcalendar/core/main.css') }}" rel='stylesheet'/>
    <link href="{{ asset('assets/fullcalendar/daygrid/main.css') }}" rel='stylesheet'/>
    <link href="{{ asset('assets/fullcalendar/timegrid/main.css') }}" rel='stylesheet'/>
    <link href="{{ asset('assets/fullcalendar/list/main.css') }}" rel='stylesheet'/>
    <!--jquery-->
    {{-- <script src="{{ asset('js/jquery.js') }}" defer></script>
    <script src="{{ asset('js/jquery-2.2.4.min.js') }}"></script> --}}
    <!--icons fa-->
    <script src="{{asset('js/icons.js')}}"></script>
    <!--alert-->
    <link rel="stylesheet" href="{{asset('css/sweetalert.css')}}">
    <script src="{{asset('js/sweetAlert.js')}}"></script>
    {{-- <link href="{{ asset('css/select2-bootstrap4.css') }}" rel='stylesheet'/> --}}
</head>
<body>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
{{-- @include('sweet::alert') --}}
@include('sweetalert::alert')
<!-- loader Start -->
<div id="loading">
    <div id="loading-center">
        <div class="loader">
            <div class="cube">
                <div class="sides">
                    <div class="top"></div>
                    <div class="right"></div>
                    <div class="bottom"></div>
                    <div class="left"></div>
                    <div class="front"></div>
                    <div class="back"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper">
    @yield("content")
</div>
@include('partials._body_footer')
</body>
</html>
