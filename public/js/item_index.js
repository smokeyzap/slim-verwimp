$(document).ready(function () {
    var csrf_name = $('input[name=csrf_name]').val()
    var csrf_value =  $('input[name=csrf_value]').val()
    var pt;
    pt = $('#product_table').DataTable({
        "ajax": "api/getbicycles",
        "pageLength": 3
    });

    //add to fav
    window.addToFav = function (item_number) {
        $.ajax({
            url: 'api/addtofavorite',
            method: 'post',
            data: "csrf_name="+csrf_name+"&csrf_value="+csrf_value+"&item_number="+item_number,
            success: function (data) {
                if (data == 'added') {
                    $('.empty').toggleClass("glyphicon-star glyphicon-star-empty");
                    toastr.success('Added to favorite')
                } else {
                    $('.fill').toggleClass("glyphicon-star-empty glyphicon-star");
                    toastr.success('Removed from favorite')
                }
            }
        });
        
    }

    //add to cart
    $('#txt_quantity').blur(function () {
        var quantity = $('#txt_quantity').val();
        var item_number = $('#item_number').text()

        $.ajax({
            url: 'api/postaddtocart',
            method: 'post',
            data: "csrf_name="+csrf_name+"&csrf_value="+csrf_value+"&item_number="+item_number+"&quantity="+quantity,
            success: function (data) {
                toastr.success(data)
            }
        });
    });

    $('body').on('click', '.img-rounded',function () {
        $('#product_img_modal').modal();
    });

    $('#product_table tbody').on('click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            pt.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        var data = pt.row( this ).data();
        
        $.ajax({
            url: 'api/getarticledetails',
            method: 'post',
            data: "csrf_name="+csrf_name+"&csrf_value="+csrf_value+"&item_number="+data[0],
            success: function (data) {
                var res='';
                $.each (data.data[0].artprop, function (key, value) {
                res +=
                    '<tr>'+
                        '<td>'+value.characteristic+'</td>'+
                        '<td>'+value.value+'</td>'+
                '</tr>';
                });
                $('#characteristic_table').html("<table style='overflow-y:scroll;height:300px;display:block;' class='table table-striped table-condensed'>" + res + "</table")
                // if (parseInt(data[1].open_stocks) > parseInt(data[0].minimum_stock)) {
                //     $('#stock_status').html('<span class="label label-success">Sufficient</span>');
                // }
                // if (parseInt(data[1].open_stocks) <= parseInt(data[0].minimum_stock)) {
                //     $('#stock_status').html('<span class="label label-warning">Limited</span>');
                // } 
                // if (parseInt(data[1].open_stocks) <= 0) {
                //     $('#stock_status').html('<span class="label label-danger">Out of stock</span>');
                // }
                if (data.data[0].favorite == true) {
                    $('#favorite_status').html('<a href=\'#\' onclick=\'addToFav("'+data.data[0].item_number+'")\' title=\'remove from favorite\'><span class=\'fill glyphicon glyphicon-star\' aria-hidden=\'true\'></span></a>')
                } else {
                    $('#favorite_status').html('<a href=\'#\' onclick=\'addToFav("'+data.data[0].item_number+'")\' title=\'add to favorite\'><span class=\'empty glyphicon glyphicon-star-empty\' aria-hidden=\'true\'></span></a>')
                }

                $('#item_number').html(data.data[0].item_number);
                $('#txt_quantity').val(data.data[0].quantity);
                $('#barcode').html(data.data[0].barcode);
                $('#name').html(data.data[0].item_name);
                $('#brand').html(data.data[0].brand);
                $('#packaging').html(data.data[0].packaging);
                $('#suggested_retail_price').html(data.data[0].suggested_retail_price);
                $('#current_purchase_price').html(parseFloat(data.data[0].purchase_price).toFixed(2));
                $('#your_price').html(parseFloat(data.data[0].your_price).toFixed(2));
                var str = data.data[0].description;
                $('#description').html(str.replace('"', ""));
                
                if (data.data[0].image == "") {
                    $('#product_image').html("<img src='http://via.placeholder.com/150x150' class='img-rounded'>");
                    $('#prod_img').html("<img src='http://via.placeholder.com/560x400?text=Product image' class='img-rounded'>");    
                } else {
                    $('#product_image').html('<img src=\'../public/img/'+data.data[0].image+'\' width=\'150\' height=\'150\' class=\'img-rounded\'>');
                    $('#prod_img').html('<img src=\'../public/img/'+data.data[0].image+'\' width=\'560\' height=\'400\' class=\'img-rounded\'>');
                }
            }
        });
    });





    $('#group_list').on('changed.jstree', function (e, data) {
        // var loMainSelected = data;
        // var result = uiGetParents(loMainSelected)
        // var text = result.split('>').map(function(item) {
        //     return item.trim()
        // })

        var i, j, r = [];
        for(i = 0, j = data.selected.length; i < j; i++) {
            r.push(data.instance.get_node(data.selected[i]).text);
        }
        var param = r.join(', ');
        console.log(param)

        
        // $('#product_table').DataTable({
        //     "ajax": "api/getarticles/"+param,
        //     "destroy": true,
        //     //"pageLength": 3
        // });

        
    }).jstree({
        "checkbox" : {
            "keep_selected_style" : false
        },
        "core": {
            'force_text':true,
            'themes': {
                'icons': false,
                'stripes': true,
                'variant': 'large'
            }
        }
    });

    function uiGetParents(loSelectedNode) {
        try {
            var lnLevel = loSelectedNode.node.parents.length;
            var lsSelectedID = loSelectedNode.node.id;
            var loParent = $("#" + lsSelectedID);
            var lsParents =  loSelectedNode.node.text + ' >';
            for (var ln = 0; ln <= lnLevel -1 ; ln++) {
                var loParent = loParent.parent().parent();
                if (loParent.children()[1] != undefined) {
                    lsParents += loParent.children()[1].text + " > ";
                }
            }
            if (lsParents.length > 0) {
                lsParents = lsParents.substring(0, lsParents.length - 1);
            }
            return lsParents;
        }
        catch (err) {
            alert('Error in uiGetParents');
        }
    }
});