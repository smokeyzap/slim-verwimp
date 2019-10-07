$(document).ready(function () {
    var csrf_name = $('input[name=csrf_name]').val();
    var csrf_value =  $('input[name=csrf_value]').val();

    //update cart quantity
    window.update_cart = function (item_number, quantity) {
        console.log(item_number)
        console.log(quantity)
    }

    window.updateQty = function (qty, item_number) 
    {
    
        if (qty == 0)
        {
            //if quantity is 0, remove from the product
            var c = confirm('Would you like to remove the product from the order?')
            if (c) {
                window.location = item_number
            } else {
                window.location = ""
            }
        } else {
            //update qty from order page
            window.location = item_number + '/' + qty 
        }
    }
    // $('#txt_quantity').blur(function () {
    //     $('.item_number').each(function(){
    //        alert($(this).val());
    //     });

    //     return;

    //     $.ajax({
    //         url: 'api/postaddtocart',
    //         method: 'post',
    //         data: "csrf_name="+csrf_name+"&csrf_value="+csrf_value+"&item_number="+item_number+"&quantity="+quantity,
    //         success: function (data) {
    //             toastr.success(data)
    //         }
    //     });
    // });
})