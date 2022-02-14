
function choose_responsible(){
    let register_type = $('#register_type').val();
    let country_provider = $('#provider_countries_id').val();
    let client_id = $('#client_name').val();
    let country_client = $('#client_country_id').val();


    if( register_type==null || register_type=='' ||
        country_provider==null || country_provider=='' ||
        country_client==null || country_client==''
    ){
        $('#box_register_assumed_by').addClass('d-none');
        $('#register_assumed_by').removeAttr('required');
        $('select#register_assumed_by option').removeAttr('selected');
        $('#responsable').addClass('d-none');
        return;
    }

    let provider_type = (country_client == country_provider)? 'N' : 'I';

    let datos = new FormData();
    datos.append('_token', token);
    datos.append('register_type', register_type);
    datos.append('provider_type', provider_type);
    datos.append('client_id', client_id);
    datos.append('country_client_id', country_client);

    $.ajax({
        type: 'POST',
        url: route_pricelist,
        dataType: 'json',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {

            if(data.price_list.length >= 2){
                $('#register_assumed_by').attr('required',true);
                $('select#register_assumed_by option').removeAttr('selected');
                $("select#register_assumed_by option[value='P']").prop('selected', true)
                $('#box_register_assumed_by').removeClass('d-none');
                $('#responsable').removeClass('d-none');
                let text = $("select#register_assumed_by option:selected").text();
                $('#responsable-text').text('El responsable del pago será el '+text);
            }else{
                $('#box_register_assumed_by').addClass('d-none');
                $('#register_assumed_by').removeAttr('required');
                $('select#register_assumed_by option').removeAttr('selected');
                // $("select#register_assumed_by option[value='C']").prop('selected', true)
                let assumed = 'El responsable del pago será el Cliente';
                if(data.price_list.length == 1 && data.price_list[0].register_assumed_by == "P"){
                    assumed = 'El responsable del pago será el Proveedor'
                }
                if(data.price_list.length == 0){
                    assumed = 'No hay responsable de pago para esta combinación en la lista de precios, la solicitud no sera creada'
                }
                $('#responsable').removeClass('d-none');
                $('#responsable-text').text(assumed);
            }
        }
    })
}

$('#register_type').on('change', function () {
    choose_responsible();
});

$('#provider_countries_id').on('change', function () {
    choose_responsible();
});

$('#client_name').on('change', function () {
    choose_responsible();
});

$('#client_country_id').on('change', function () {
    choose_responsible();
});

$('select#register_assumed_by').on('change', function () {

    let text = $("select#register_assumed_by option:selected").text();
    $('#responsable-text').text('El responsable del pago será el '+text);
});
