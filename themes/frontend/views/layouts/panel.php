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
    $cs->registerCssFile($baseUrl.'/css/font-awesome.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-select.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-theme.css?3.8');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-panel-theme.css?3.8');
    $cs->registerCssFile($baseUrl.'/css/responsive-theme.css?3.8');
    $cs->registerCssFile($baseUrl.'/css/responsive-panel-theme.css?3.8');

    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.mousewheel.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.nicescroll.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/bootstrap-select.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.script.js?3.8', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/panel.script.js?3.8', CClientScript::POS_END);
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
                    echo (Yii::app()->user->avatar == '') ? Yii::app()->theme->baseUrl . '/svg/default-user.svg' : Yii::app()->baseUrl . '/uploads/users/' . Yii::app()->user->avatar;
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
            <?php $this->renderPartial('//partial-views/_sidebar_menu') ?>
        </div>
    </div>
    <div class="content">
        <div class="top-bar">
            <?php if(isset(Yii::app()->user->clinic) && Yii::app()->user->clinicCount > 1):?>
                <div class="pull-right">
                    <a href="<?= $this->createUrl('/clinics/panel') ?>"><h5><?php echo Yii::app()->user->clinic->clinic_name;?></h5></a>
                    <a title="خروج از <?= CHtml::encode('"'.Yii::app()->user->clinic->clinic_name.'"'); ?>" href="<?php echo Yii::app()->createUrl('/clinics/panel/leave');?>" class="btn btn-danger btn-sm">خروج از <?= CHtml::encode('"'.Yii::app()->user->clinic->clinic_name.'"'); ?></a>
                </div>
            <?php endif;?>
            <div class="icons">
                <a href="<?php echo $this->createUrl('/users/public/setting');?>" title="تنظیمات"><i class="setting-icon"></i></a>
                <a href="<?php echo $this->createUrl('/users/public/logout');?>" title="خروج"><i class="logout-icon"></i></a>
            </div>
        </div>
        <div class="middle-box">
            <?php echo $content;?>
        </div>
        <?php $this->renderPartial('//partial-views/panel-footer');?>
    </div>
</div>

</body>
</html>