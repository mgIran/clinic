<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $this->siteName.(!empty($this->pageTitle)?' - '.$this->pageTitle:'') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ad ,tablo ,تابلو ,آگهی , دیوار ، شیپور">
    <meta name="author" content="App Mobasheri">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <?php
    $baseUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    Yii::app()->clientScript->registerCoreScript('jquery');
    ?>
    <!-- Fav and Touch and touch icons -->
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/css/fontiran.css">
    <?php

    $cs->registerCssFile($baseUrl.'/css/bootstrap.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-reset.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-responsive.min.css');
    $cs->registerCssFile($baseUrl.'/css/abound.css');
    $cs->registerCssFile($baseUrl.'/css/rtl.css');
    $cs->registerCssFile($baseUrl.'/css/style-blue.css');
    $cs->registerCssFile($baseUrl.'/css/font-awesome.css');
    $cs->registerCssFile($baseUrl.'/css/jquery.tagit.css');
    $cs->registerCssFile($baseUrl.'/css/tagit.ui-zendesk.css');

    $cs->registerCoreScript('jquery.ui');
    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
    $cs->registerScriptFile($baseUrl.'/js/plugins/tag-it.min.js');
    $cs->registerScriptFile($baseUrl.'/js/scripts.js');
    ?>
</head>

<body>

<section id="navigation-main">
    <!-- Require the navigation -->
    <?php require_once('tpl_navigation.php')?>
</section><!-- /#navigation-main -->

<section class="container">
    <div class="container-fluid">
        <!-- Include content pages -->
        <div style="width: 35%;margin: 0 auto">
            <div class="portlet">
                <div class="portlet-decoration">
                    <div class="">ورود به مدیریت</div>
                </div>
                <div class="portlet-content" style="padding: 10%;position:relative;">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Require the footer -->
<?php require_once('tpl_footer.php')?>

</body>
</html>