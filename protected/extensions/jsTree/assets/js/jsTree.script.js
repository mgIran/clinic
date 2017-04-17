$(function() {
    $("#modules_tree").jstree({
        "plugins": ["wholerow", "checkbox"],
        "core": {
            "themes": {
                "name": "proton",
                "responsive": true
            }
        }
    });
    $("#modules_tree").jstree().close_all();
    $("body").on("click", 'input[type="submit"]', function () {
        var selectedElmsIds={};
        var selectedElms = $("#modules_tree").jstree("get_selected", true);

        var moduleName = '',
            controllerName = '',
            actionName = '';
        $.each(selectedElms, function (index) {
            var id = this.id.split('-');
            moduleName = id[0];
            controllerName = id[1];
            actionName = id[2];

            if (controllerName != undefined && actionName != undefined) {
                if(eval("selectedElmsIds['" + moduleName+"']") == undefined)
                    eval("selectedElmsIds['" + moduleName + "'] = {}");

                if(eval("selectedElmsIds['" + moduleName+"']['"+controllerName+"']") == undefined)
                    eval("selectedElmsIds['" + moduleName+"']['"+controllerName+"'] = {}");

                eval("selectedElmsIds['" + moduleName+"']['"+controllerName+"']["+index+"]=\'" + actionName + "\';");
            }
        });
        $("#js-tree-permissions").val(JSON.stringify(selectedElmsIds));
    });
});