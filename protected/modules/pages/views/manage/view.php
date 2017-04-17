<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle= Yii::app()->name . ' - '.$model->title;

?>
<div class="page rtl col-lg-12 col-md-12 col-sm-12 col-xs-12 index" >
    <div class="panel-body">
        <h2 class="page-header">
            <?= $model->title; ?>
        </h2>
        <div class="content">
            <?= $model->summary; ?>
        </div>
    </div>
</div>