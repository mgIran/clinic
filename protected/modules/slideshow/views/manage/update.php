<?php
/* @var $this SlideShowManageController */
/* @var $model Slideshow */
/* @var $image UploadedFiles */
/* @var $mobileImage UploadedFiles */

$this->breadcrumbs=array(
	'مدیریت تصاویر'=>array('admin'),
	$model->title,
	'ویرایش',
);
?>

<h1>ویرایش تصویر <?php echo $model->title; ?></h1>

<?php $this->renderPartial('_form', compact('model', 'image', 'mobileImage')); ?>
