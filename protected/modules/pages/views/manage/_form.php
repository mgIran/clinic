<?php
/* @var $this PagesManageController */
/* @var $model Pages */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pages-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<? $this->renderPartial('//layouts/_flashMessage'); ?>

    <?
    if($this->categorySlug == 'free' || $this->categorySlug == 'document'):
    ?>
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
    <?
    endif;
    ?>
	<div class="row">
		<?php echo $form->labelEx($model,'summary'); ?>
        <?
        $this->widget('ext.ckeditor.CKEditor', array(
            'model'=>$model,
            'attribute'=>'summary',
        ));
        ?>
		<?php echo $form->error($model,'summary'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش',array('class' => 'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->