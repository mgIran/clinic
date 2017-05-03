<?php
/* @var $this Controller */
/* @var $content string */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    $cs->registerCssFile($baseUrl.'/css/bootstrap-theme.css?3.8');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-panel-theme.css?3.8');
    $cs->registerCssFile($baseUrl.'/css/responsive-theme.css?3.8');
    $cs->registerCssFile($baseUrl.'/css/responsive-panel-theme.css?3.8');

    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.mousewheel.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.nicescroll.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.script.js?3.8', CClientScript::POS_END);
    $cs->registerScript('sidebar-scroll', '
        $(".sidebar").niceScroll({cursorcolor: "#8b8b8b",
            cursorborder: "none",
            autohidemode:true, cursorwidth:5, railalign:"left"
        });
    ');
    ?>
</head>
<body style="overflow: hidden;">
<div class="overlay fade"></div>
<div class="panel-page">
    <div class="sidebar">
        <a class="navbar-brand hidden-lg hidden-md hidden-sm" href="<?php echo Yii::app()->createUrl('//'); ?>"><img src="<?php echo Yii::app()->theme->baseUrl.'/svg/logo-white.svg'?>" alt="<?php echo Yii::app()->name;?>"><h1>کتـــــابیـــــک</h1></a>
        <div class="profile">
            <div class="profile-image">
                <img src="<?php
                if(Yii::app()->user->auth_mode == 'site') {
                    echo (Yii::app()->user->avatar == '') ? Yii::app()->theme->baseUrl . '/svg/default-user.svg' : Yii::app()->baseUrl . '/uploads/users/avatar/' . Yii::app()->user->avatar;
                } else
                    echo Yii::app()->user->avatar;
                ?>" alt="<?= $this->userDetails->getShowName(); ?>">
            </div>
            <div class="profile-info">
                <h4><?= $this->userDetails->getShowName(); ?></h4>
                <small><?= $this->userDetails->user->email; ?></small>
                <?php if(isset(Yii::app()->user->clinic)):?>
                    <span><?= $this->userDetails->roleLabels[Yii::app()->user->roles]; ?></span>
                <?php endif;?>
            </div>
        </div>
        <div class="list-group">
            <h5>کاربری</h5>
            <?php if(!isset(Yii::app()->user->clinic)):?>
                <a title="داشبورد" href="<?php echo Yii::app()->createUrl('/dashboard');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='dashboard')?' active':'';?>">داشبورد</a>
            <?php endif;?>
            <a title="تنظیمات" href="<?php echo Yii::app()->createUrl('/users/public/setting');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='users/public/setting')?' active':'';?>">تنظیمات</a>
            <?php if(isset(Yii::app()->user->clinic)):?>
                <?php if(Yii::app()->user->roles == 'clinicAdmin'):?>
                    <h5>اپراتور</h5>
                <?php elseif(Yii::app()->user->roles == 'doctor'):?>
                    <h5>پزشک</h5>
                <?php endif;?>
                <a title="پرسنل" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel')?' active':'';?>">پرسنل</a>
                <a title="پرسنل" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel')?' active':'';?>">پرسنل</a>
                <a title="پرسنل" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel')?' active':'';?>">پرسنل</a>
                <a title="پرسنل" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel')?' active':'';?>">پرسنل</a>
                <a title="پرسنل" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel')?' active':'';?>">پرسنل</a>
                <a title="پرسنل" href="<?php echo Yii::app()->createUrl('/clinics/panel');?>" class="list-group-item<?php echo (Yii::app()->request->pathInfo=='clinics/panel')?' active':'';?>">پرسنل</a>
            <?php endif;?>
        </div>
    </div>
    <div class="content">
        <div class="top-bar">
            <?php if(isset(Yii::app()->user->clinic)):?>
                <div class="pull-right">
                    <a href="<?= $this->createUrl('/clinics/panel') ?>"><h5><?php echo Yii::app()->user->clinic->clinic_name;?></h5></a>
                    <a title="خروج از <?= "\"s\""; ?>" href="<?php echo Yii::app()->createUrl('/clinics/panel/leave');?>" class="btn btn-danger btn-sm">خروج از <?= CHtml::encode('"'.Yii::app()->user->clinic->clinic_name.'"'); ?></a>
                </div>
            <?php endif;?>
            <div class="icons">
                <a href="<?php echo $this->createUrl('/users/public/setting');?>" title="تنظیمات"><i class="setting-icon"></i></a>
                <a href="<?php echo $this->createUrl('/users/public/logout');?>" title="خروج"><i class="logout-icon"></i></a>
            </div>
        </div>
        <?php echo $content;?>
        <?php $this->renderPartial('//partial-views/panel-footer');?>
    </div>
</div>

</body>
</html>