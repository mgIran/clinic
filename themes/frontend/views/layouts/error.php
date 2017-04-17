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
    <meta name="description" content="<?= $this->description?>">
    <title><?= $this->siteName.(!empty($this->pageTitle)?' - '.$this->pageTitle:'') ?></title>

    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/fontiran.css">
    <?php
    $baseUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    Yii::app()->clientScript->registerCoreScript('jquery');

    $cs->registerCssFile($baseUrl.'/css/bootstrap.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-rtl.min.css');
    $cs->registerCssFile($baseUrl.'/css/bootstrap-panel-theme.css');
    $cs->registerCssFile($baseUrl.'/css/responsive-panel-theme.css');

    $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/jquery.script.js', CClientScript::POS_END);
    ?>
</head>
<body>

<?php echo $content;?>

</body>
</html>