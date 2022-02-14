<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }} "></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<!-- Appear JavaScript -->
<script src="{{ asset('assets/js/jquery.appear.js') }}"></script>
<!-- Countdown JavaScript -->
<script src="{{ asset('assets/js/countdown.min.js') }}"></script>
<!-- Counterup JavaScript -->
<script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
<!-- Wow JavaScript -->
<script src="{{ asset('assets/js/wow.min.js') }}"></script>
<!-- Apexcharts JavaScript -->
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>
<!-- Slick JavaScript -->
<script src="{{ asset('assets/js/slick.min.js') }}"></script>
<!-- Select2 JavaScript -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<!-- Owl Carousel JavaScript -->
<script src="{{ asset('assets/js/owl.carousel.min.js') }} "></script>
<!-- Magnific Popup JavaScript -->
<script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
<!-- Smooth Scrollbar JavaScript -->
<script src="{{ asset('assets/js/smooth-scrollbar.js') }} "></script>
<!-- morris chart JavaScript -->
<script src="{{ asset('assets/js/morris.js') }} "></script>
<script src="{{ asset('assets/js/raphael-min.js') }} "></script>
<script src="{{ asset('assets/js/morris.min.js') }} "></script>
<!-- lottie JavaScript -->
<script src="{{ asset('assets/js/lottie.js') }} "></script>
<script src="{{ asset('assets/js/core.js') }}"></script>
<script src="{{ asset('assets/js/charts.js') }}"></script>
<script src="{{ asset('assets/js/animated.js') }}"></script>

{{-- <script src="{{ asset('assets/js/highcharts.js') }}"></script>
<script src="{{ asset('assets/js/highcharts-3d.js') }}"></script>
<script src="{{ asset('assets/js/highcharts-more.js') }}"></script> --}}

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

{{-- <script src="{{ asset('assets/fullcalendar/core/main.js') }}"></script>
<script src="{{ asset('assets/fullcalendar/daygrid/main.js') }}"></script>
<script src="{{ asset('assets/fullcalendar/timegrid/main.js') }}"></script>
<script src="{{ asset('assets/fullcalendar/list/main.js') }}"></script> --}}

<!-- Chart Custom JavaScript -->
<script src="{{ asset('assets/js/chart-custom.js') }} "></script>

<!-- Custom JavaScript -->
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/jquery.blockUI.js') }}"></script>


<script>
     $( window ).load(function() {
        timeError = "{{ __('La consulta tardo demasiado tiempo en responder') }}";
        otherError = "{{ __('Ha surgido un error, por favor intente nuevamente') }}";
        // En todos los llamados ajax se bloquea la pantalla mostrando el mensaje 'Por favor espere'...
        $.blockUI.defaults.message = `{{ __('Cargando') }}<br><img src="{{ asset('assets/images/loader.gif') }}" width='100px'><br>{{ __('Por favor espere') }} ...`;
        $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
    });
</script>

<script>
    $(document).ready(function() {
        $('.select2').select2({width: '100%'});

        // Evita el envio del formulario si ya se esta enviando
        $("body").on("submit", "form", function() {
            $(this).submit(function() {
                return false;
            });
            return true;
        });
    });

</script>

@yield("scripts")
