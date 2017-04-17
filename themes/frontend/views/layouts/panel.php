<?php
/* @var $this Controller */
/* @var $content string */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="keywords" content="<?= $this->keywords ?>">
    <meta name="description" content="<?= $this->description?> ">
    <title><?= $this->siteName.(!empty($this->pageTitle)?' - '.$this->pageTitle:'') ?></title>

    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/fontiran.css">
    <?php
    $baseUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    Yii::app()->clientScript->registerCoreScript('jquery');

    $cs->registerCssFile($baseUrl.'/css/bootstrap.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-rtl.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-select.min.css');
    $cs->registerCssFile($baseUrl.'/css/font-awesome.css');
    $cs->registerCssFile($baseUrl.'/css/persian-datepicker-0.4.5.min.css');
    $cs->registerCssFile($baseUrl.'/css/persian-datepicker-custom.css');
    $cs->registerCssFile($baseUrl.'/css/owl.carousel.css');
    $cs->registerCssFile($baseUrl.'/css/owl.theme.default.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-panel-theme.css?3.8');
    $cs->registerCssFile($baseUrl.'/css/responsive-panel-theme.css?3.8');

    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/bootstrap-select.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/defaults-fa_IR.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.cookie.js');
    $cs->registerScriptFile($baseUrl.'/js/persian-datepicker-0.4.5.min.js');
    $cs->registerScriptFile($baseUrl.'/js/persian-date.js');
    $cs->registerScriptFile($baseUrl.'/js/owl.carousel.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.script.js?3.8', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/panel.script.js?3.8', CClientScript::POS_END);
    ?>
</head>
<body>
<div class="overlay fade"></div>
<nav class="navbar navbar-default">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed">
            <span class="sr-only">نمایش / پنهان کردن منو</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="pin"></div>
        <a class="navbar-brand hidden-xs" href="<?php echo Yii::app()->createUrl('//'); ?>"><img src="<?php echo Yii::app()->theme->baseUrl.'/svg/logo-white.svg'?>" alt="<?php echo Yii::app()->name;?>"><h1>کتـــــابیـــــک</h1></a>
    </div>

    <div class="navbar-custom hidden-xs" id="mobile-menu">
        <?php
        echo CHtml::beginForm(array('/book/search'),'get',array('class'=>'navbar-form navbar-center'))
        ?>
        <div class="form-group">
            <?php echo CHtml::textField('term',isset($_GET['term'])?CHtml::encode($_GET['term']):'',array('id'=>'search-term','class'=>'form-control','placeholder'=>'جستجو کنید...','autocomplete' => 'off')) ?>
            <span class="search-btn-icon">
                <?php echo CHtml::submitButton('',array('name'=>'','class'=>'btn btn-default')) ?>
            </span>
        </div>
        <div class="search-suggest-box">
            <div class="search-entries"></div>
        </div>
        <?php
        echo CHtml::endForm();
        ?>
        <ul class="navbar-buttons navbar-left">
            <li><a href="<?= $this->createUrl('/tickets/manage/');?>"><i class="messages-icon"></i></a></li>
            <li><a href="<?php echo $this->createUrl('/users/public/notifications');?>"><i class="notification-icon"></i><?php if(count($this->userNotifications)!=0):?><span class="badge"><?php echo count($this->userNotifications);?></span><?php endif;?></a></li>
            <li><a href="<?php echo $this->createUrl('/logout');?>"><i class="logout-icon"></i></a></li>
        </ul>
    </div>
</nav>
<div class="page-container">
    <div class="sidebar">
        <a class="navbar-brand hidden-lg hidden-md hidden-sm" href="<?php echo Yii::app()->createUrl('//'); ?>"><img src="<?php echo Yii::app()->theme->baseUrl.'/svg/logo-white.svg'?>" alt="<?php echo Yii::app()->name;?>"><h1>کتـــــابیـــــک</h1></a>
        <div class="profile">
            <div class="profile-image">
                <img src="<?php
                if(Yii::app()->user->auth_mode == 'site')
                {
                    echo (Yii::app()->user->avatar=='')?Yii::app()->theme->baseUrl.'/images/default-user.svg':Yii::app()->baseUrl.'/uploads/users/avatar/'.Yii::app()->user->avatar;
                }else
                    echo Yii::app()->user->avatar;
                ?>" alt="<?= $this->userDetails->getShowName(); ?>">
                <div class="profile-badges">
                    <a href="<?php echo Yii::app()->createUrl('users/public/bookmarked');?>" class="profile-badges-left"><i class="bookmark-icon"></i><span><?php echo Controller::parseNumbers(number_format(count($this->userDetails->user->bookmarkedBooks), 0, '.', '.'));?></span>نشان شده</a>
                    <a href="<?php echo Yii::app()->createUrl('/users/credit/buy');?>" class="profile-badges-right"><i class="credit-icon"></i><span><?php echo Controller::parseNumbers(number_format($this->userDetails->credit, 0, '.', '.'));?></span>تومان</a>
                </div>
            </div>
            <div class="profile-info">
                <h4><?= $this->userDetails->getShowName(); ?></h4>
                <small><?= $this->userDetails->user->email; ?></small>
                <span><?= $this->userDetails->roleLabels[Yii::app()->user->roles] ?></span>
            </div>
        </div>
        <div class="list-group">
            <h5>کاربری</h5>
            <a data-toggle="tooltip" data-placement="left" title="داشبورد" href="<?php echo Yii::app()->createUrl('/dashboard');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='dashboard')?' active':'';?>"><i class="dashboard-icon"></i><span class="text">داشبورد</span></a>
            <a data-toggle="tooltip" data-placement="left" title="کتابخانه من" href="<?php echo Yii::app()->createUrl('/library');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='library')?' active':'';?>"><i class="my-library-icon"></i><span class="text">کتابخانه من</span></a>
            <a data-toggle="tooltip" data-placement="left" title="سفارشات من" href="<?php echo Yii::app()->createUrl('/shop/order/history');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='shop/order/history')?' active':'';?>"><i class="cart-icon"></i><span class="text">سفارشات من</span></a>
            <a data-toggle="tooltip" data-placement="left" title="تراکنش ها" href="<?php echo Yii::app()->createUrl('/transactions');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='transactions')?' active':'';?>"><i class="transaction-icon"></i><span class="text">تراکنش ها</span></a>
            <a data-toggle="tooltip" data-placement="left" title="پشتیبانی" href="<?php echo Yii::app()->createUrl('tickets/manage');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='tickets/manage')?' active':'';?>"><i class="support-icon"></i><span class="text">پشتیبانی</span><?php
                if($mc = Users::getTicketNewMessageCount())
                    echo '<span class="badge">'.Controller::parseNumbers($mc).'</span>';
                ?></a>
            <a data-toggle="tooltip" data-placement="left" title="دستگاه های فعال" href="<?php echo Yii::app()->createUrl('users/public/sessions');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/sessions')?' active':'';?>"><i class="session-icon"></i><span class="text">دستگاه های فعال</span></a>
        </div>
        <?php if(Yii::app()->user->roles=='publisher'):?>
            <div class="list-group">
                <h5>ناشرین</h5>
                <a data-toggle="tooltip" data-placement="left" title="کتاب ها" href="<?php echo Yii::app()->createUrl('publishers/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='publishers/panel')?' active':'';?>"><i class="my-library-icon"></i><span class="text">کتاب ها</span></a>
                <a data-toggle="tooltip" data-placement="left" title="تخفیفات" href="<?php echo Yii::app()->createUrl('publishers/panel/discount');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='publishers/panel/discount')?' active':'';?>"><i class="discount-icon"></i><span class="text">تخفیفات</span></a>
                <a data-toggle="tooltip" data-placement="left" title="گزارش فروش" href="<?php echo Yii::app()->createUrl('publishers/panel/sales');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='publishers/panel/sales')?' active':'';?>"><i class="chart-icon"></i><span class="text">گزارش فروش</span></a>
                <a data-toggle="tooltip" data-placement="left" title="تسویه حساب" href="<?php echo Yii::app()->createUrl('publishers/panel/settlement');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='publishers/panel/settlement')?' active':'';?>"><i class="payment-icon"></i><span class="text">تسویه حساب</span></a>
                <a data-toggle="tooltip" data-placement="left" title="پروفایل ناشر" href="<?php echo Yii::app()->createUrl('publishers/panel/account');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='publishers/panel/account')?' active':'';?>"><i class="user-icon"></i><span class="text">پروفایل ناشر</span></a>
            </div>
        <?php endif;?>
    </div>
    <div class="content">
        <?php echo $content;?>
    </div>
    <div class="footer">
        <div class="pull-right">
            <a href="<?php echo Yii::app()->createUrl('logout');?>"><i class="logout-icon"></i></a>
            <a href="<?php echo Yii::app()->createUrl('users/public/setting');?>"><i class="setting-icon"></i></a>
        </div>
        <div class="pull-left copyright">
            © 2016 Ketabic
        </div>
    </div>
</div>

</body>
</html>