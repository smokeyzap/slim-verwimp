$(document).ready(function () {
    var pt;
    var csrf_name = $('input[name=csrf_name]').val();
    var csrf_value =  $('input[name=csrf_value]').val();

    pt = $('#return_warranty_other_history').DataTable({
        "pageLength": 5
    });


    $('#return_warranty_other_history tbody').on('click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            pt.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        var order_id = pt.row( this ).data();

        $('#return_warranty_history_items').DataTable({
            "ajax": "api/getreturnwarrantyotherhistory/"+order_id[0],
            "destroy": true,
            "pageLength": 5
        });
    });
})