<?php
/* @var $this PublishersPanelController */
/* @var $model Users */
?>

<h1>افزودن ناشر</h1>

<?php $this->renderPartial('//layouts/_flashMessage');?>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#info">اطلاعات کاربری</a></li>
    <li class="disabled"><a>اطلاعات پروفایل</a></li>
</ul>

<div class="tab-content">
    <div id="info" class="tab-pane fade in active">
        <?php $this->renderPartial('_publisher_form', array(
            'model'=>$model
        )); ?>
    </div>
</div>