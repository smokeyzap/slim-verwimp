$(document).ready(function () {
    pt = $('#product_table').DataTable({
    	"ajax": "api/getitemlist",
        "processing": true,
        "serverSide": true,
    });

    $('#product_table tbody').on('click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            pt.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        data = pt.row( this ).data();
        
    });

    $('#select_button').on('click', function () {
        $('#item_number').val(data[1])
        $('#item_name').val(data[2])
        $('#select_items_modal').modal('hide')
    });
})