$(document).ready(function () {
    var pt;
    var oi;
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
        $.ajax({
            url: 'api/getorderitems',
            method: 'post',
            data: "csrf_name="+csrf_name+"&csrf_value="+csrf_value+"&id="+order_id[0],
            success: function (data) {
                
            }
        });
    });
})