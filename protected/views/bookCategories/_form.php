<?php
/* @var $this BookCategoriesController */
/* @var $model BookCategories */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-categories-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'icon_color'); ?>
		<?php
		$this->widget('ext.SMiniColors.SActiveColorPicker', array(
			'model' => $model,
			'attribute' => 'icon_color',
			'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
			'options' => array(), // jQuery plugin options
			'htmlOptions' => array('class'=>'ltr text-left'), // html attributes
		));
		?>
		<?php echo $form->error($model,'icon_color'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model,'parent_id',BookCategories::model()->getParents(),array('prompt' => '-')); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ذخیره' ,array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->