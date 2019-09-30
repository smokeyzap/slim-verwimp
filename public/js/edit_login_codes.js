$(document).ready(function () {
    if ($('#is_full_access').is(':checked')){
        $('#permissions').hide()
    } else {
        $('#permissions').show()
    }


    $('#is_full_access').on('click', function () {
        if ($(this).is(':checked')){
            $('#permissions').hide()
        } else {
            $('#permissions').show()
        }
    });
});