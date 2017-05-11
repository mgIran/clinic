var $window = $(window),
    $body = $("body");
$(document).ready(function() {
    $("[data-toggle='tooltip']").tooltip({
        trigger:'hover'
    });

    // filters ajax in panel
    var ajaxGridUpdateTimeout;
    $body.on("keyup", ".ajax-grid-search", function(){
        var $this = $(this),
            $form = $this.parents("form"),
            $url = $form.attr("action"),
            $formData = $form.serialize(),
            $gridView = $form.find(".grid-view");
        clearTimeout(ajaxGridUpdateTimeout);
        ajaxGridUpdateTimeout = setTimeout(function () {
            $.fn.yiiGridView.update($gridView.attr("id"), {data: $formData});
        },300);
    });
    //
});