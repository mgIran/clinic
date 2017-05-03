<?php
/* @var $this UsersExpertisesController */
/* @var $model Expertises */
/* @var $form CActiveForm */
/* @var $icon array */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'expertises-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <?= $this->renderPartial('//partial-views/_flashMessage'); ?>

	<p class="note">فیلدهای <span class="required">*</span>دار اجباری هستند.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'icon',array('class'=> 'block')); ?>
		<?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
			'id' => 'uploaderIcon',
			'model' => $model,
			'name' => 'icon',
			'maxFiles' => 1,
			'maxFileSize' => 0.5, //MB
			'url' => Yii::app()->createUrl('/users/expertises/upload'),
			'deleteUrl' => Yii::app()->createUrl('/users/expertises/deleteUpload'),
			'acceptedFiles' => '.jpg, .jpeg, .png, .svg',
			'serverFiles' => $icon,
			'onSuccess' => '
                var responseObj = JSON.parse(res);
                if(responseObj.status){
                    {serverName} = responseObj.fileName;
                    $(".uploader-message").html("");
                }
                else{
                    $(".uploader-message").html(responseObj.message);
                    this.removeFile(file);
                }
            ',
		)); ?>
		<?php echo $form->error($model,'icon'); ?>
		<div class="uploader-message error"></div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->