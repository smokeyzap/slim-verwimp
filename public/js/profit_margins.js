$(document).ready(function () {
    $('#group_list').on('changed.jstree', function (e, data) {

        var loMainSelected = data;
        var result = uiGetParents(loMainSelected);
        var text = result.split('>').map(function(item) {
            return item.trim()
        })

        var title_title
        var sub_group_title
        var group_title

        //console.log(text)
        //click from sub child
        if (text.length == 5) {
            title_title = text[0]
            sub_group_title = text[1]
            group_title = text[2]
        }

        //click from child
        if (text.length == 4) {
            title_title = ""
            sub_group_title = text[0]
            group_title = text[1]
        }

        //click from parent
        if (text.length == 3) {
            title_title = ""
            sub_group_title = ""
            group_title = text[0]
        }

        $('#grouptitle').text(group_title)
        $('#subgrouptitle').text(sub_group_title)
        $('#title_title').text(title_title)
        // var i, j, r = [];
        // for(i = 0, j = data.selected.length; i < j; i++) {
        //   r.push(data.instance.get_node(data.selected[i]).text);
        // }
        // var cat_subcat = r.join(', ');
        // console.log(cat_subcat)
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