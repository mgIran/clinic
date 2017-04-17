<?php
/* @var $this UsersManageController */
/* @var $model Users */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'مدیریت کاربران'=>array('admin'),
);

$this->menu=array(
	array('label'=>'لیست کاربران', 'url'=>array('admin')),
);
?>
<? $this->renderPartial('//layouts/_flashMessage'); ?>
<h1>تغییر وضعیت کاربر <?= $model->email ?></h1>

<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'users-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation'=>false,
	)); ?>
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',$model->statusLabels); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class' => 'btn btn-success')); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->