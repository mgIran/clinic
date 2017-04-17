var $body = $("body");
$(function() {
    $("[title]").tooltip();
    $(window).scroll(function () {
        if ($(window).scrollTop() > 85)
            $(".navbar.navbar-default").addClass('scroll-mode');
        else
            $(".navbar.navbar-default").removeClass('scroll-mode');
    });
    if($('.selectpicker').length && $.fn.selectpicker)
        $('.selectpicker').selectpicker();
    // ajax search
    $body.on("keyup","#search-term",function(e){
        var $this = $(this),
            $form = $this.parents('form');
        if($.inArray(e.keyCode,[37,38,39,40]) === -1)
            return searchNavbarKeydown($this);
    });

    $body.on("focus","#search-term",function(){
        var $this = $(this),
            $form = $this.parents('form');
        if($this.val().length > 0)
            $form.find('.search-suggest-box').addClass('open');
    });

    /**
     * search ajax call action
     * @param $this
     */
    function searchNavbarKeydown($this) {
        var $form = $this.parents('form');
        if($this.val().length > 0) {
            $.ajax({
                url: $form.attr("action"),
                type: "GET",
                dataType: "JSON",
                data: {term: $this.val()},
                beforeSend: function () {
                    // $form.find('.search-suggest-box').addClass('opening').find('.loading-container').show();
                },
                success: function (data) {
                    $form.find('.search-suggest-box').removeClass('opening').addClass('open').find('.loading-container').hide();
                    if (data.status)
                        $form.find('.search-suggest-box .search-entries').html(data.html);
                    else
                        $form.find('.search-suggest-box .search-entries').html(data.message);
                }
            });
        }
    }
    $body.on("click",function(event){
        var $target = $(event.target),
            close = true;
        if($target.is(".navbar-form, .navbar-form *"))
            close = false;
        if(close)
            $('.navbar-form .search-suggest-box').removeClass('open');
    });
    // end ajax search

    if ($('.slider').length != 0) {
        var loop = $('.slider').attr('data-loop');
        if (typeof loop === 'undefined')
            loop = false;
        $('.slider').owlCarousel({
            items: 1,
            dots: false,
            nav: true,
            navText: ["<i class='arrow-icon'></i>", "<i class='arrow-icon'></i>"],
            autoplay: true,
            autoplayTimeout: 8000,
            autoplayHoverPause: true,
            rtl: true,
            loop: loop
        });

        $('.slider-overlay-nav').click(function () {
            if ($(this).hasClass('slider-next'))
                $('.slider .owl-controls .owl-nav .owl-next').trigger('click');
            else if ($(this).hasClass('slider-prev'))
                $('.slider .owl-controls .owl-nav .owl-prev').trigger('click');
            return false;
        });
    }

    $('.is-carousel').each(function () {
        initCarousel($(this));
    });

    $('.news .arrow-icon').click(function () {
        if ($(this).hasClass('next'))
            $('.news .owl-controls .owl-nav .owl-next').trigger('click');
        else if ($(this).hasClass('prev'))
            $('.news .owl-controls .owl-nav .owl-prev').trigger('click');
        return false;
    });

    $('.tabs .nav a').on('shown.bs.tab', function () {
        var thisTag = $(this);
        var thisTabId = thisTag.attr('href');
        var owlClasses = $(thisTabId).find('.is-carousel');
        owlClasses.trigger('destroy.owl.carousel');
        owlClasses.removeClass('owl-loaded');
        initCarousel(owlClasses);
    });

    $('.back-to-top').click(function () {
        $('html').animate({scrollTop: 0}, 1000);
        return false;
    });

    if ($('#categories-modal').length > 0) {
        var catsList = null,
            content = null;
        $('#categories-modal').on('shown.bs.modal', function () {
            if (catsList == null && content == null) {
                catsList = $(this).find('.cats-list').niceScroll({
                    cursorcolor: '#999',
                    cursorborder: 'none',
                    autohidemode: false,
                    railalign: 'left',
                    cursorwidth: '4px',
                    cursorborderradius: '4px'
                });
                content = $(this).find('.cats-content').niceScroll({
                    cursorcolor: '#999',
                    cursorborder: 'none',
                    autohidemode: false,
                    railalign: 'left',
                    cursorwidth: '4px',
                    cursorborderradius: '4px'
                });
            } else {
                catsList.resize();
                content.resize();
            }
        });
    }

    $body.on("click", "[data-toggle='modal']", function () {
        var $this = $(this),
            $url = $this.data("return-url"),
            $target = $this.data("target");
        if($target == '#login-modal' && typeof $url !== "undefined")
        {
            $("#login-modal").find("#returnUrl").val($url);
            $("#login-modal").find("#google-login-btn").attr('href', baseUrl+"/googleLogin?return-url="+$url);
        }
    });

    //paralax
    var $window = $(window);
    $('.paralax .content').each(function () {
        var $bgobj = $(this);
        var yPos = -( ($window.scrollTop() - $bgobj.offset().top + 30) / 5);
        var ycss = 'background-position: 50% ' + yPos + 'px !important; transition: none;';
        $bgobj.attr('style', ycss);

        $(window).scroll(function () {
            var yPos = -( ($window.scrollTop() - $bgobj.offset().top + 30) / 5);
            var ycss = 'background-position: 50% ' + yPos + 'px !important; transition: none;';
            $bgobj.attr('style', ycss);
        });
    });


    $(window).resize(function () {
        var slider = $('.slider');
        if (slider.length != 0) {
            var loop = $('.slider').attr('data-loop');
            if (typeof loop === 'undefined')
                loop = false;
            slider.trigger('destroy.owl.carousel');
            slider.html(slider.find('.owl-stage-outer').html()).removeClass('owl-loaded');
            slider.owlCarousel({
                items: 1,
                dots: false,
                nav: true,
                navText: ["<i class='arrow-icon'></i>", "<i class='arrow-icon'></i>"],
                autoplay: true,
                autoplayTimeout: 8000,
                autoplayHoverPause: true,
                rtl: true,
                loop: loop
            });
            $('.slider-overlay-nav').click(function () {
                if ($(this).hasClass('slider-next'))
                    $('.slider .owl-controls .owl-nav .owl-next').trigger('click');
                else if ($(this).hasClass('slider-prev'))
                    $('.slider .owl-controls .owl-nav .owl-prev').trigger('click');
                return false;
            });
        }
    });
});
function initCarousel($this) {
    var nestedItemSelector = $this.data('item-selector'),
        dots = ($this.data('dots') == 1) ? true : false,
        nav = ($this.data('nav') == 1) ? true : false,
        responsive = $this.data('responsive'),
        loop = ($this.data('loop') == 1) ? true : false,
        autoPlay = ($this.data('autoplay') == 1) ? true : false,
        autoPlayHoverPause = ($this.data('autoplay-hover-pause') == 1) ? true : false,
        mouseDrag = ($this.data('mouse-drag') == 1) ? true : false;
    if (typeof nestedItemSelector == 'undefined') {
        $this.owlCarousel({
            slideBy: 1,
            loop: loop,
            autoplay: autoPlay,
            items: 1,
            dots: dots,
            nav: nav,
            autoplayHoverPause: autoPlayHoverPause,
            mouseDrag: mouseDrag,
            navText: ["<i class='arrow-icon'></i>", "<i class='arrow-icon'></i>"],
            responsive: responsive,
            rtl: true
        });
    } else {
        $this.owlCarousel({
            slideBy: 1,
            loop: loop,
            autoplay: autoPlay,
            items: 1,
            nestedItemSelector: nestedItemSelector,
            dots: dots,
            nav: nav,
            autoplayHoverPause: autoPlayHoverPause,
            mouseDrag: mouseDrag,
            navText: ["<i class='arrow-icon'></i>", "<i class='arrow-icon'></i>"],
            responsive: responsive,
            rtl: true
        });
    }
}


function submitAjaxForm(form ,url ,loading ,callback) {
    loading = typeof loading !== 'undefined' ? loading : null;
    callback = typeof callback !== 'undefined' ? callback : null;
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        dataType: "json",
        beforeSend: function () {
            if(loading)
                loading.show();
        },
        success: function (html) {
            if(loading)
                loading.hide();
            if (typeof html === "object" && typeof html.status === 'undefined') {
                $.each(html, function (key, value) {
                    $("#" + key + "_em_").show().html(value.toString()).parent().removeClass('success').addClass('error');
                });
            }else
                eval(callback);
        },
        error: function (data) {
            if(loading)
                loading.hide();
            alert(data.responseText);
        }
    });
}