var $window = $(window),
    $body = $("body");
    $sidebar = $(".sidebar");
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

    var $panel_cookie=1;
    if($window.width() > 768 && $.cookie('p-s-mode'))
    {
        $panel_cookie = $.cookie('p-s-mode');
        if($panel_cookie == 1)
            $body.addClass('sidebar-mini');
        else
            $body.removeClass('sidebar-mini');
    }
    $body.on('click','.pin',function () {
        $body.toggleClass('sidebar-mini');
        if($body.hasClass('sidebar-mini'))
            $panel_cookie=1;
        else
            $panel_cookie=0;
        var time = 10 * 365 ;
        $.cookie('p-s-mode', $panel_cookie,{expires: time ,path:'/'});
    });

    $body.on("click",".navbar-toggle",function(){
        if($body.hasClass("open-sidebar")) {
            $body.removeClass("open-sidebar");
            $(".overlay").removeClass("in");
        }else {
            $window.scrollTop(0);
            $body.addClass("open-sidebar");
            $(".overlay").addClass("in");
        }
    });

    $body.on("click",".overlay",function(event){
        $body.removeClass("open-sidebar");
        $(".overlay").removeClass("in");
    });
    $window.resize(function () {
        if($window.width() > 768)
        {
            $body.removeClass("open-sidebar");
            $(".overlay").removeClass("in");
            if($panel_cookie == 1)
                $body.addClass('sidebar-mini');
            else
                $body.removeClass('sidebar-mini');
        }else if($window.width() < 768)
        {
            $body.removeClass("sidebar-mini");
        }
    });
});

$window.load(function() {
    if ($('.sidebar').length != 0) {
        var height,
            navHeight;
        if($window.width()>=768)
            navHeight= 140;
        else if($window.width() < 768)
            navHeight= 120;
        if (typeof CKEDITOR == 'undefined') {
            height = ($('body').height() < $(window).height()) ? $(window).height() : $('body').height();
            $('.sidebar').height(height - navHeight);
        }else
            CKEDITOR.on('instanceReady', function () {
                height = ($('body').height() < $(window).height()) ? $(window).height() : $('body').height();
                $('.sidebar').height(height - navHeight);
            });
    }
});