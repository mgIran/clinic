<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shop-order-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">فیلد های دارای <span class="required">*</span> الزامی هستند.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'delivery_address_id'); ?>
		<?php echo $form->textField($model,'delivery_address_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'delivery_address_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'billing_address_id'); ?>
		<?php echo $form->textField($model,'billing_address_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'billing_address_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ordering_date'); ?>
		<?php echo $form->textField($model,'ordering_date',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'ordering_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_date'); ?>
		<?php echo $form->textField($model,'update_date',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'update_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'payment_method'); ?>
		<?php echo $form->textField($model,'payment_method',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'payment_method'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shipping_method'); ?>
		<?php echo $form->textField($model,'shipping_method',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'shipping_method'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->