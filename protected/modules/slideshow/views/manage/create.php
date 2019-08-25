<?php
/* @var $this SlideShowManageController */
/* @var $model Slideshow */
/* @var $image UploadedFiles */
/* @var $mobileImage UploadedFiles */

$this->breadcrumbs = array(
    'مدیریت' => array('admin'),
    'افزودن',
);

$this->menu = array(
    array('label' => 'مدیریت تصاویر', 'url' => array('admin')),
);
?>
<h1>افزودن تصویر جدید</h1>

<?php $this->renderPartial('_form', compact('model', 'image', 'mobileImage')); ?>