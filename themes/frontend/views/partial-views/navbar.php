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
                <li><a href="<?= Yii::app()->createAbsoluteUrl('//') ?>">صفحه اصلی</a></li>
                <li><a href="#">درباره ما</a></li>
                <li><a href="#">تماس با ما</a></li>
                <?php
                if(Yii::app()->user->isGuest):
                ?>
                    <li><a href="<?php echo Yii::app()->createUrl('/login');?>">ورود</a></li>
                    <li><a href="#">ثبت نام</a></li>
                <?php
                elseif(Yii::app()->user->type == 'admin'):
                ?>
                    <li><a href="<?php echo Yii::app()->createUrl('/admins/dashboard');?>">پنل مدیریت</a></li>
                    <li><a class="text-danger" href="<?= Yii::app()->createUrl('/logout')?>">خروج</a></li>
                <?php
                elseif(Yii::app()->user->type == 'user'):
                ?>
                    <li><a href="<?php echo Yii::app()->createUrl('/dashboard');?>">داشبورد</a></li>
                    <li><a class="text-danger" href="<?= Yii::app()->createUrl('/logout')?>">خروج</a></li>
                <?php
                endif;
                ?>
            </ul>
            <ul class="navbar-socials hidden-xs hidden-sm">
                <li><a href="#"><i class="facebook-icon"></i></a></li>
                <li><a href="#"><i class="instagram-icon"></i></a></li>
                <li><a href="#"><i class="telegram-icon"></i></a></li>
            </ul>
        </div>
    </div>
</nav>