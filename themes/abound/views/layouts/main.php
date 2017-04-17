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
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/css/fontiran.css">
	<?php

	  $cs->registerCssFile($baseUrl.'/css/bootstrap.min.css');
      $cs->registerCssFile($baseUrl.'/css/bootstrap-reset.css');
      $cs->registerCssFile($baseUrl.'/css/bootstrap-select.min.css');
	  $cs->registerCssFile($baseUrl.'/css/bootstrap-responsive.min.css');
      $cs->registerCssFile($baseUrl.'/css/persian-datepicker-0.4.5.min.css');
      $cs->registerCssFile($baseUrl.'/css/persian-datepicker-custom.css');
      $cs->registerCssFile($baseUrl.'/css/font-awesome.css');
      $cs->registerCssFile($baseUrl.'/css/abound.css?2');
      $cs->registerCssFile($baseUrl.'/css/rtl.css?2');
      $cs->registerCssFile($baseUrl.'/css/style-blue.css?2');
      $cs->registerCoreScript('jquery.ui');
      $cs->registerScriptFile($baseUrl.'/js/persian-datepicker-0.4.5.min.js');
      $cs->registerScriptFile($baseUrl.'/js/persian-date.js');
	  $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
      $cs->registerScriptFile($baseUrl.'/js/bootstrap-select.min.js', CClientScript::POS_END);
      $cs->registerScriptFile($baseUrl.'/js/defaults-fa_IR.min.js', CClientScript::POS_END);
      $cs->registerScriptFile($baseUrl.'/js/scripts.js?2');
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
            <?php echo $content; ?>
    </div>
</section>

<!-- Require the footer -->
<?php require_once('tpl_footer.php')?>

  </body>
</html>