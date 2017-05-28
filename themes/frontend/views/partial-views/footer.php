<div class="footer">
    <div class="container">
        <ul class="nav navbar-nav">
            <li><a href="<?= Yii::app()->createUrl('/about') ?>">درباره ما</a></li>
            <li><a href="<?= Yii::app()->createUrl('/contactus') ?>">تماس با ما</a></li>
            <li><a href="<?= Yii::app()->createUrl('/help') ?>">راهنما</a></li>
<!--            <li><a href="--><?//= Yii::app()->createUrl('/terms') ?><!--">شرایط و ضوابط</a></li>-->
        </ul>
        <ul class="navbar-socials">
            <li><a href="#"><i class="facebook-icon"></i></a></li>
            <li><a href="#"><i class="instagram-icon"></i></a></li>
            <li><a href="#"><i class="telegram-icon"></i></a></li>
        </ul>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="pull-right ltr"><?php $this->renderPartial('//partial-views/_copyright');?></div>
            <div class="pull-left">
                <a href="https://t.me/rahbod" target="_blank" title="Rahbod">
                    <img src="<?php echo Yii::app()->theme->baseUrl.'/svg/rahbod.svg';?>" alt="Rahbod">
                </a>
            </div>
        </div>
    </div>
</div>