<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle= Yii::app()->name . ' - '.$model->title;
?>
<div class="page">
    <div class="container page-content">
        <h1><?php echo $model->title; ?></h1>
        <div class="text-content"><?= $model->summary; ?></div>
    </div>
</div>