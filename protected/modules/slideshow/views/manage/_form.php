<?php
/* @var $this SlideShowManageController */
/* @var $model Slideshow */
/* @var $form CActiveForm */
/* @var $image UploadedFiles */
/* @var $mobileImage UploadedFiles */
?>

<div class="form">
	<?php $this->renderPartial('//partial-views/_flashMessage'); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'slideshow-form',
	'enableAjaxValidation'=>true,
));
?>
	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100,'class' => 'form-control')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	<!--
	<div class="form-group">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('form-groups'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
-->
	<div class="form-group">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
			'id' => 'uploaderFile',
			'model' => $model,
			'name' => 'image',
			'maxFiles' => 1,
			'maxFileSize' => 2, //MB
			'url' => $this->createUrl('upload'),
			'deleteUrl' => $this->createUrl('deleteUpload'),
			'acceptedFiles' => '.jpeg, .jpg, .png',
            'serverFiles' => $image,
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
        <?php echo $form->error($model,'image'); ?>
        <div class="uploader-message error"></div>
	</div>

    <div class="form-group">
		<?php echo $form->labelEx($model,'mobile_image'); ?>
		<?php $this->widget('ext.dropZoneUploader.dropZoneUploader', array(
			'id' => 'uploaderMobileFile',
			'model' => $model,
			'name' => 'mobile_image',
			'maxFiles' => 1,
			'maxFileSize' => 2, //MB
			'url' => $this->createUrl('uploadMobile'),
			'deleteUrl' => $this->createUrl('deleteUploadMobile'),
			'acceptedFiles' => '.jpeg, .jpg, .png',
            'serverFiles' => $mobileImage,
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
        <?php echo $form->error($model,'mobile_image'); ?>
        <div class="uploader-message error"></div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'link'); ?>
		<?php echo $form->textField($model,'link',array('size'=>60,'maxlength'=>2000,'class' => 'form-control')); ?>
		<?php echo $form->error($model,'link'); ?>
	</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره',array('class' => 'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->