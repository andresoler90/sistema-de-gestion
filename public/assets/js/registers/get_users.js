$('#client_name').on('change', function() {

    $('#requesting_users_id').empty();
    $('#client_country_id').empty();

    $('#requesting_users_id').append(`
        <option selected="selected" value="">Seleccione uno...</option>
    `);

    $('#client_country_id').append(`
        <option selected="selected" value="">Seleccione uno...</option>
    `);

    $("select#register_type").prop("selectedIndex", 0).change();
    $("select#provider_countries_id").prop("selectedIndex", 0).change();
    $("select#identification_type").prop("selectedIndex", 0).change();

    if(this.value!= null && this.value!= ''){
        $.get(`../../../get/users/${this.value}`, function (res, sta) {

            res.country.forEach(element => {
                $('#client_country_id').append(`
                    <option value=${element.id}>${element.name}</option>
                `);
            });

            res.users.forEach(element => {
                $('#requesting_users_id').append(`
                    <option value=${element.id}>${element.name}</option>
                `);
            });
        })
    }
});
