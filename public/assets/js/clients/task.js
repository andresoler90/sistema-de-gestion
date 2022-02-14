$( document ).on('ready',function() {
    $('.stages').next().addClass('cerrado');

    $('.stages').on('click',function() {

        $('.stages').next().removeClass('abierto').addClass('cerrado');
        $('.stages').children('i').addClass('fa-angle-down text-primary').removeClass('fa-angle-up text-white');
        $('.stages').removeClass('bg-primary text-white');
        $("div > span", '.stages').removeClass('bg-white text-primary').addClass('bg-primary text-white');

        $(this).next().toggleClass('d-none');

        if(!$(this).next().hasClass("d-none")){
            $(this).next().addClass('abierto').removeClass('cerrado');
            $(this).children('i').removeClass('fa-angle-down text-primary').addClass('text-white fa-angle-up');
            $(this).addClass('bg-primary text-white');
            $("div > span", this).removeClass('bg-primary text-white').addClass('bg-white text-primary');
        }
        $('.cerrado').addClass('d-none');

    });
});

$('.btn-task').on('click',function() {
    let datos = new FormData();
    let task_id = $(this).data('option');
    let stage = $(this).data('stage');
    let option = false;

    if ($(`#option${task_id}`).is(':checked')) {
        option = true;
    }

    datos.append('user_id', user_id);
    datos.append('task_id', task_id);
    datos.append('option', option);

    datos.append('_token', token);

    $.ajax({
        type: 'POST',
        url: ruta,
        dataType: 'json',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data.status == 200) {
                if(data.number){
                    $(`#stage${stage}`).text(parseInt($(`#stage${stage}`).text())+data.number);
                    $('.tasks').text(parseInt($('.tasks').text())+data.number);
                }

                Swal.fire(data.title, data.msg,'success');
            } else {
                Swal.fire(data.title, data.msg, 'warning');
            }
        }
    })
});

