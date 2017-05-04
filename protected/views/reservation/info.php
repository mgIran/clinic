<?
/* @var $this ReservationController */
?>

<div class="inner-page">
    <?php $this->renderPartial('_steps', array('active'=>2));?>

    <div class="page-help">
        <div class="container">
            <h4>ثبت اطلاعات بیمار</h4>
            <ul>
                <li>اطلاعات خود را بصورت صحیح و کامل وارد نمایید.</li>
                <li>وارد کردن شماره همراه الزامی می باشد. با لغو یا تغییر نوبت برای شما پیامی ارسال خواهد شد.</li>
            </ul>
        </div>
    </div>

    <div class="form-container">
        <div class="container">
            <form>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <input type="text" placeholder="کد ملی">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown">نوع بیمه<i class="gray-arrow-icon"></i></button>
                            <ul class="dropdown-menu">
                                <li><a href="#">آزاد</a></li>
                                <li><a href="#">تامین اجتماعی</a></li>
                                <li><a href="#">خدمات درمانی</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <input type="text" placeholder="نام و نام خانوادگی">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <input type="text" placeholder="نام پدر">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <input type="text" placeholder="تلفن همراه">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <input type="text" placeholder="پست الکترونیکی">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <textarea placeholder="توضیحات"></textarea>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <input type="text" placeholder="کد امنیتی">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <small class="desc">در پایان فرایند رزرو نوبت، سیستم برای شما کد رهگیری در نظر می گیرد. لطفا هنگام مراجعه به درمانگاه، کد رهگیری را همراه داشته باشید.</small>
                        <input type="submit" class="btn-red pull-left" value="ثبت">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>