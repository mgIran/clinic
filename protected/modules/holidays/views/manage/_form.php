<?php
/* @var $this HolidaysManageController */
/* @var $model Holidays */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $this->renderPartial('//partial-views/_flashMessage')?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'holidays-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
    <?php
    if($model->isNewRecord):
    ?>
	<div class="row">
        <?php echo $form->labelEx($model, 'date'); ?>
        <?php $this->widget('ext.PDatePicker.PDatePicker', array(
            'id' => 'date-picker',
            'model' => $model,
            'attribute' => 'date',
            'htmlOptions' => array(
                'autocomplete' => 'off'
            ),
            'options' => array(
                'maxDate' => (strtotime(date('Y/m/d 00:00',time()))-1)*1000,
                'format' => 'DD MMMM YYYY',
            )
        )); ?>
	</div>
    <?php
    endif;
    ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش', array('class' => 'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->