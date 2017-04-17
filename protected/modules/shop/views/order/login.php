<div class="page">
    <div class="page-heading">
        <div class="container">
            <h1>ورود به سیستم</h1>
        </div>
    </div>
    <div class="container page-content">
        <div class="white-box cart">
            <?php $this->renderPartial('/order/_steps', array('point' => 0));?>
            <div class="login-box">
                <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                    <div class="content">
                        <div class="login-icon hidden-xs"></div>
                        <div class="text">
                            <p>عضو سایت ما هستید؟</p>
                            <p>برای تکمیل فرایند خرید وارد شوید</p>
                            <button href="#" data-toggle="modal" data-target="#login-modal" class="btn-blue" id="login-modal-trigger" data-return-url="<?= $this->route; ?>">ورود به حساب کاربری</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                    <div class="content">
                        <div class="signup-icon hidden-xs"></div>
                        <div class="text">
                            <p>تازه وارد هستید؟</p>
                            <p>برای تکمیل فرایند خرید ثبت نام کنید</p>
                            <button href="#" data-toggle="modal" data-target="#login-modal" class="btn-green" id="register-modal-trigger" data-return-url="<?= $this->route; ?>">ساخت حساب کاربری</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Yii::app()->clientScript->registerScript("login-modal-tab-trigger", '
$("#login-modal-trigger").click(function(){
    $("#login-modal .modal-title").text("ورود به پنل کاربری");
    $("#login-modal #login-tab-trigger").tab("show");
});

$("#register-modal-trigger").click(function(){
    $("#login-modal .modal-title").text("ثبت نام");
    $("#login-modal #register-tab-trigger").tab("show");
});
');?>