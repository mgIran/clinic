<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle= Yii::app()->name . ' - '.$model->title;
?>
<div class="page">
    <div class="page-heading">
        <div class="container">
            <h1><?php echo $model->title; ?></h1>
        </div>
    </div>
    <div class="container page-content">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="text-content"><?= $model->summary; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>