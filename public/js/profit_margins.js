$(document).ready(function () {
    $('#group_list').on('changed.jstree', function (e, data) {
        
        var i, j, r = [];
        for(i = 0, j = data.selected.length; i < j; i++) {
          r.push(data.instance.get_node(data.selected[i]).text);
        }
        var cat_subcat = r.join(', ');
        console.log(cat_subcat)
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
});