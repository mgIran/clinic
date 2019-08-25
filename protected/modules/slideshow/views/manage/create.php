<?php
/* @var $this SlideShowManageController */
/* @var $model Slideshow */
/* @var $image UploadedFiles */
/* @var $mobileImage UploadedFiles */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت تصاویر', 'url'=>array('admin')),
);
?>

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">افزودن تصویر جدید</h3>
			<a href="<?= $this->createUrl('admin') ?>" class="btn btn-primary btn-sm pull-left">
				<span class="hidden-xs">بازگشت</span>
				<i class="fa fa-arrow-left"></i>
			</a>
		</div>
		<div class="box-body">
			<?php $this->renderPartial('_form', compact('model', 'image', 'mobileImage')); ?>
		</div>
	</div>