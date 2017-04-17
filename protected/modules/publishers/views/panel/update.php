<?php
/* @var $this PublishersPanelController */
/* @var $model Users */
/* @var $nationalCardImage array */
/* @var $registrationCertificateImage array */
?>

<h1>ویرایش اطلاعات ناشر</h1>

<?php $this->renderPartial('//layouts/_flashMessage');?>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#info">اطلاعات کاربری</a></li>
    <li><a data-toggle="tab" href="#profile">اطلاعات پروفایل</a></li>
</ul>

<div class="tab-content">
    <div id="info" class="tab-pane fade in active">
        <?php $this->renderPartial('_publisher_form', array(
            'model'=>$model
        )); ?>
    </div>

    <div id="profile" class="tab-pane">
        <?php $this->renderPartial('_publisher_profile', array(
            'model'=>$model->userDetails,
            'nationalCardImage'=>$nationalCardImage,
            'registrationCertificateImage'=>$registrationCertificateImage,
        )); ?>
    </div>
</div>