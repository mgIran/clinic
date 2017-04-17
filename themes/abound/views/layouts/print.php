<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
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
	$cs->registerCssFile($baseUrl.'/css/font-awesome.css');
	$cs->registerCssFile($baseUrl.'/css/abound.css?2');
	$cs->registerCssFile($baseUrl.'/css/rtl.css?2');
	$cs->registerCssFile($baseUrl.'/css/style-blue.css?2');
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
	$cs->registerScriptFile($baseUrl.'/js/bootstrap-select.min.js', CClientScript::POS_END);
	$cs->registerScriptFile($baseUrl.'/js/defaults-fa_IR.min.js', CClientScript::POS_END);
	$cs->registerScriptFile($baseUrl.'/js/scripts.js?2');
	?>
</head>

<body>
<section class="container">
	<?php echo $content; ?>
</section>
</body>
</html>