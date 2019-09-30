$(document).ready(function () {
    $('#is_full_access').on('click', function () {
        if ($(this).is(':checked')){
            $('#permissions').hide()
        } else {
            $('#permissions').show()
        }
    });
});