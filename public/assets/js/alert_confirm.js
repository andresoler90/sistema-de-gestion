var alert_title = $('.alert_title').text();
var alert_confirmButtonText = $('.alert_confirmButtonText').text();
var alert_cancelButtonText = $('.alert_cancelButtonText').text();

$('.btn-destroy').on('click', function() {
    Swal.fire({
        title: alert_title,
        showCancelButton: true,
        confirmButtonText: alert_confirmButtonText,
        cancelButtonText: alert_cancelButtonText,
    }).then((result) => {
        if (result.isConfirmed) {
            $(this).parent().trigger( "submit" );
        }
    })
});
