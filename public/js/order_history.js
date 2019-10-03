$(document).ready(function () {
    var pt;
    var csrf_name = $('input[name=csrf_name]').val();
    var csrf_value =  $('input[name=csrf_value]').val();

    pt = $('#order_order').DataTable({
        "pageLength": 5,
        "order": [0,'desc']
    });


    $('#order_order tbody').on('click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            pt.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        var order_id = pt.row( this ).data();

        $('#order_items').DataTable({
            "ajax": "api/getorderitems/"+order_id[0],
            "destroy": true
        });
    });
})