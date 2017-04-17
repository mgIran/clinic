var $body = $body;
$(function() {
    $body.on("click", "#register-tab-trigger", function () {
        $("#login-modal .modal-title").text("ثبت نام");
    });

    $body.on("click", "#login-tab-trigger", function () {
        $("#login-modal .modal-title").text("ورود به پنل کاربری");
    });

    $body.on("click", ".address-list .address-item", function (e) {
        if (!$(e.target).hasClass("edit-link") && !$(e.target).hasClass("remove-link")) {
            $(".address-list .address-item").removeClass("selected");
            $(this).addClass("selected");
            $(this).find("input[type='radio']").prop("checked", true);
        }
    });

    $body.on("click", ".shipping-methods-list .shipping-method-item", function () {
        $(".shipping-methods-list .shipping-method-item").removeClass("selected");
        $(this).addClass("selected");
        $(this).find("input[type='radio']").prop("checked", true);
    });

    // cart scripts
    $body.on("change", ".quantity", function () {
        $.ajax({
            url: baseUrl + "/shop/cart/updateQty",
            type: "POST",
            dataType: "JSON",
            data: {book_id: $(this).data("id"), qty: $(this).val()},
            beforeSend: function () {
                $("#basket-loading").fadeIn();
            },
            success: function (data) {
                if (data.status) {
                    $("#basket-table").html(data.table);
                    $(".navbar-default .navbar-nav li a .cart-count").text(data.countCart);
                }
                $("#basket-loading").fadeOut();
            }
        });
    });

    $body.on("click", ".remove", function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr("href"),
            type: "POST",
            dataType: "JSON",
            data: {book_id: $(this).data("id")},
            beforeSend: function () {
                $("#basket-loading").fadeIn();
            },
            success: function (data) {
                if (data.status) {
                    $("#basket-table").html(data.table);
                    $(".navbar-default .navbar-nav li a .cart-count").text(data.countCart);
                }
                $("#basket-loading").fadeOut();
            }
        });
    });

    // shipping scripts
    $body.on("click", ".edit-link", function (e) {
        e.preventDefault();
        var $this = $(this);
        $.ajax({
            url: $this.attr("href"),
            dataType: "JSON",
            beforeSend: function () {
                $("#basket-loading").fadeIn();
            },
            success: function (data) {
                $("#add-address-modal").html($(data.content).html());
                $("#basket-loading").fadeOut();
                $("#add-address-modal").modal("show");
            }
        });
    });

    $body.on("click", ".remove-link", function (e) {
        e.preventDefault();
        if (confirm("آیا از حذف این آدرس اطمینان دارید؟")) {
            var $this = $(this);
            $.ajax({
                url: $this.attr("href"),
                type: "POST",
                data: {id: $this.data("id")},
                dataType: "JSON",
                beforeSend: function () {
                    $("#basket-loading").fadeIn();
                },
                success: function (data) {
                    $("#addresses-list-container").html(data.content);
                    $("#basket-loading").fadeOut();
                }
            });
        }
    });

    $body.on("click", "#add-address-modal #address-form input[type=\'submit\']", function () {
        var form = $("#add-address-modal #address-form");
        var loading = $("#add-address-modal.modal .loading-container");
        submitAjaxForm(
            form,
            form.attr("action"),
            loading,
            "if(html.status){ $(\'#addresses-list-container\').html(html.content); $(\'#add-address-modal #address-form input[type=\"text\"]\').val(\'\'); $(\'#add-address-modal .close\').trigger(\'click\'); $(\'#places-label\').html(\'شهرستان مورد نظر را انتخاب کنید\'); $(\'#places\').html(\'\'); $(\'#places-hidden\').val(\'\'); $(\'#towns-label\').html(\'استان مورد نظر را انتخاب کنید\'); $(\'#towns-hidden\').val(\'\'); }else $(\'#add-address-modal #summary-errors\').html(html.errors);");
        return false;
    });

    $body.on("click", "#add-address-modal-trigger", function () {
        $("#add-address-modal #address-form").attr("action", $(this).data('url'));
    });

    // payment scripts
    $body.on("click", ".payment-methods-list .payment-method-item", function(){
        $(".payment-methods-list .payment-method-item").removeClass("selected");
        $(this).addClass("selected");
        $(this).find("input[type='radio']").prop("checked", true);
    });

});