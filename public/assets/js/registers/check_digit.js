function check_digit(country,identification) {
    if(country == 43 && identification == 'NIT'){
        $('#d_check_digit').show();
        $('#check_digit').attr('required',true);
        $('#check_digit').attr('min',0);
        $('#check_digit').attr('max',9);
        // $('#identification').addClass('col-sm-8').addClass('col-lg-9');
        $('#identification').addClass('col-lg-9');
    }else{
        $('#d_check_digit').hide();
        $('#check_digit').removeAttr('required').removeAttr('min').removeAttr('max');
        // $('#identification').removeClass('col-sm-8').removeClass('col-lg-9');
        $('#identification').removeClass('col-lg-9');

        $('#alert_dv').addClass('d-none');
    }
}

$('#provider_countries_id').on('change', function() {
    let country = this.value;
    let identification = $('#identification_type').val();
    check_digit(country,identification);
});

$('#identification_type').on('change', function() {
    let country = $('#provider_countries_id').val();
    let identification = this.value;
    check_digit(country,identification);
});

$( document ).on('ready',function() {
    $('select#register_type option:first').attr('disabled', true);
    $('select#provider_countries_id option:first').attr('disabled', true);
    $('select#identification_type option:first').attr('disabled', true);
    $('select#register_assumed_by option:first').attr('disabled', true);

    let pci = $('#provider_countries_id').val()
    let country = (pci != null)?pci : $('#provider_countries').data('provider_countries_id');
    let identification = $('#identification_type').val();

    check_digit(country,identification);
});

