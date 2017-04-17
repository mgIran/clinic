<?php
/* @var $this PagesManageController*/
/* @var $model Pages */
?>
<div class="container dashboard-container">

    <? $this->renderPartial('publishers.views.panel._tab_links',array('active' => 'documents')); ?>

    <a class="btn btn-success publisher-signup-link" href="<?php echo Yii::app()->createUrl('/dashboard')?>">پنل کاربری</a>
    <div class="tab-content card-container">
        <a href="<?php echo $this->createUrl('/publishers/panel/documents');?>" class="btn btn-info pull-left">بازگشت</a>
        <h3><?= $model->title ?></h3>
        <br>
        <div class="center-block">
            <p><?= strip_tags(nl2br($model->summary)) ?></p>
        </div>
    </div>
</div>