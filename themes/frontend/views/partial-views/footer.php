<?php $purifier=new CHtmlPurifier();?>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="about">
                    <div class="title">
                        <img src="<?php echo Yii::app()->theme->baseUrl.'/svg/logo.svg';?>" alt="<?php echo Yii::app()->name;?>">
                        <h1><?php echo Yii::app()->name;?><small>نزدیکترین کتابفروشی شهر</small></h1>
                        <div class="text"><?php echo $purifier->purify($this->aboutFooter); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 col-mm-12">
                <ul class="statistics">
                    <li>افراد آنلاین<span><?= Controller::parseNumbers(number_format(Yii::app()->userCounter->getOnline())) ?></span></li>
                    <li>بازدید امروز<span><?= Controller::parseNumbers(number_format(Yii::app()->userCounter->getToday())) ?></span></li>
                    <li>بازدید دیروز<span><?= Controller::parseNumbers(number_format(Yii::app()->userCounter->getYesterday())) ?></span></li>
                    <li>پر بازدیدترین روز<span><?= Controller::parseNumbers(number_format(Yii::app()->userCounter->getMaximal())) ?></span></li>
                    <li>کل بازدیدها<span><?= Controller::parseNumbers(number_format(Yii::app()->userCounter->getTotal())) ?></span></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 col-mm-12">
                <div class="buttons">
                    <a href="<?= $this->siteAppUrls['windows'] ?>"><i class="windows-icon"></i>نسخه ویندوز</a>
                    <a href="<?= $this->siteAppUrls['android'] ?>"><i class="android-icon"></i>نسخه اندورید</a>
                </div>
                <div class="namad"><img src="<?php echo Yii::app()->baseUrl.'/uploads/enamad.png';?>"></div>
                <nav class="links">
                    <ul class="nav nav-justified">
                        <li><a href="<?= $this->createUrl('/publishers') ?>">ناشران</a></li>
                        <li><a href="<?= $this->createUrl('/help') ?>">راهنما</a></li>
                        <li><a href="<?= $this->createUrl('/contactus') ?>">تماس با ما</a></li>
                        <li><a href="<?= $this->createUrl('/about') ?>">درباره ما</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="back-to-top-wrap">
        <div class="container">
            <a class="back-to-top" href="#top" title="بازگشت به بالا"><i class="arrow-icon"></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <nav class="col-md-6 footer-social">
                <div class="row">
                    <ul class="social-list pull-right">
                        <li><a href="#" class="social-icon"><i class="facebook-icon"></i></a></li>
                        <li><a href="#" class="social-icon"><i class="twitter-icon"></i></a></li>
                    </ul>
                </div>
            </nav>
            <span class="pull-left copy-right"> © <?php echo date("Y");?> Ketabic</span>
        </div>
    </div>
</div>
<?php $this->renderPartial('//partial-views/category-modal');?>
<?php $this->renderPartial('//partial-views/login-modal');?>