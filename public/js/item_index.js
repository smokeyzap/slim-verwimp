$(document).ready(function () {
    var csrf_name = $('input[name=csrf_name]').val()
    var csrf_value =  $('input[name=csrf_value]').val()
    var pt
    pt = $('#product_table').DataTable({

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