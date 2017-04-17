<?php
/* @var $this ShopShippingController */
/* @var $model ShopShippingMethod */
/* @var $form CActiveForm */
?>

<div style="margin-top: 30px">
	<?php
	$this->renderPartial('//layouts/_flashMessage');
	?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shop-shipping-method-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>تومان
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'limit_price'); ?>
		<?php echo $form->textField($model,'limit_price'); ?>تومان
        <div class="clearfix"></div>
        <small class="description">در صورت خالی بودن و یا وارد کردن 0 شرط رایگان بودن اعمال نمیشود.</small>
		<?php echo $form->error($model,'limit_price'); ?>
	</div>

	<div class="row"><br>
		<?php echo $form->labelEx($model,'payment_method'); ?><br><br>
		<?php echo $form->checkBoxList($model,'payment_method',CHtml::listData(ShopPaymentMethod::model()->findAll(),'id','title')); ?>
		<?php echo $form->error($model,'payment_method'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش',array('class' => 'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->