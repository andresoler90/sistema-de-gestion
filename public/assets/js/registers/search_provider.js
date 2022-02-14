
$('#search').on('click', function() {
    let datos = new FormData();
    let identification = $('#identification_number').val();
    datos.append('_token', token);
    datos.append('identification', identification);

    let provider_countries_id = $('#provider_countries_id').val();
    let identification_type = $('#identification_type').val();

    if(provider_countries_id == 43  && identification_type == 'NIT'){

        let check_digit = $('#check_digit').val();
        if(check_digit== null || check_digit==''){
            $('#alert_dv').removeClass('d-none');
            return;
        }else{
            $('#alert_dv').addClass('d-none');
        }
        datos.append('check_digit', check_digit);
    }

    $.ajax({
        type: 'POST',
        url: route_provider,
        dataType: 'json',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data.status == 200) {
                let provider = data.provider;
                $('#business_name').val(provider.pv1_providerName);
                if(provider.contact!=null){
                    $('#telephone_contact').val(provider.contact.pv17_phone);
                    $('#name_contact').val(provider.contact.pv17_name);
                    $('#email_contact').val(provider.contact.pv17_email);
                }else{
                    $('#telephone_contact').val('');
                    $('#name_contact').val('');
                    $('#email_contact').val('');
                }
            }else{
                Swal.fire(
                    data.title,
                    data.msg,
                    'warning'
                );
                $('#business_name').val('');
                $('#telephone_contact').val('');
                $('#name_contact').val('');
                $('#email_contact').val('');
            }
        }
    })
});
