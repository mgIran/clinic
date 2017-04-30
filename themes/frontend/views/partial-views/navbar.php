<nav class="navbar navbar-default">
    <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mobile-navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="navbar-header pull-left">
            <a class="navbar-brand" href="#"><img src="<?php echo Yii::app()->theme->baseUrl.'/svg/logo.svg';?>" alt="<?php echo Yii::app()->name;?>"><h1>پزشک یار<small>رزرو اینترنتی ویزیت پزشک</small></h1></a>
        </div>
        <div class="collapse navbar-collapse" id="mobile-navbar">
            <ul class="nav navbar-nav">
                <li><a href="#">صفحه اصلی</a></li>
                <li><a href="#">درباره ما</a></li>
                <li><a href="#">تماس با ما</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('/login');?>">ورود</a></li>
                <li><a href="#">ثبت نام</a></li>
            </ul>
            <ul class="navbar-socials hidden-xs hidden-sm">
                <li><a href="#"><i class="facebook-icon"></i></a></li>
                <li><a href="#"><i class="instagram-icon"></i></a></li>
                <li><a href="#"><i class="telegram-icon"></i></a></li>
            </ul>
        </div>
    </div>
</nav>