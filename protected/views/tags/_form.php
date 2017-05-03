<?php
/* @var $this ClassTagsManageController */
/* @var $model ClassTags */
/* @var $form CActiveForm */
?>
<? $this->renderPartial('//partial-views/_flashMessage'); ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'class-tags-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->