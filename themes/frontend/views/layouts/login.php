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
    $cs->registerCssFile($baseUrl.'/css/owl.carousel.css');
    $cs->registerCssFile($baseUrl.'/css/owl.theme.default.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-panel-theme.css?3.8');
    $cs->registerCssFile($baseUrl.'/css/responsive-panel-theme.css?3.8');

    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/owl.carousel.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.script.js?3.8', CClientScript::POS_END);
    ?>
</head>
<body class="login-page">
<div class="container">
<!--    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-2  login-box">-->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 login-box">
        <a class="login-logo" href="<?php echo Yii::app()->createUrl('/site');?>"><img src="<?php echo Yii::app()->theme->baseUrl.'/svg/logo-white.svg';?>" alt="<?php echo Yii::app()->name;?>"><h1>کــــــــتابیــــــــــک<small>نزدیکترین کتابفروشی شهر</small></h1></a>
        <?php echo $content;?>
        <div class="copyright">© 2016 Ketabic</div>
    </div>
</div>
</body>
</html>