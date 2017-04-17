<?php
/* @var $this TicketsManageController */
/* @var $model Tickets */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tickets-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">
		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php echo $form->labelEx($model,'subject'); ?>
			<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255,'class' => 'form-control')); ?>
			<?php echo $form->error($model,'subject'); ?>
		</div>

		<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php echo $form->labelEx($model,'department_id'); ?>
			<?php echo $form->dropDownList($model,'department_id',CHtml::listData(TicketDepartments::model()->findAll(),'id','title'),array('maxlength'=>10,'class' => 'form-control')); ?>
			<?php echo $form->error($model,'department_id'); ?>
		</div>

		<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php echo $form->labelEx($model,'text'); ?>
			<?php echo $form->textArea($model,'text',array('rows'=>15,'class' => 'form-control')); ?>
			<?php echo $form->error($model,'text'); ?>
		</div>
		<div id="file-uploader-box" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse">
			<?= CHtml::label('فایل' ,'uploaderImages' ,array('class' => 'control-label')); ?>
			<?php
			$this->widget('ext.dropZoneUploader.dropZoneUploader', array(
				'id' => 'uploaderImages',
				'model' => $model,
				'name' => 'attachment',
				'maxFiles' => 1,
				'maxFileSize' => 2, //MB
				'url' => $this->createUrl('/tickets/manage/upload'),
				'deleteUrl' => $this->createUrl('/tickets/manage/deleteUploaded'),
				'acceptedFiles' => '.jpg, .jpeg, .png, .pdf, .doc, .docx, .zip',
				'serverFiles' => array(),
				//				'data' => array('book_id'=>$model->id),
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
			));
			?>
			<?php echo $form->error($model,'attachment'); ?>
			<div class="uploader-message error"></div>
		</div>
	</div>
	<?php echo CHtml::button('فایل ضمیمه',array('class' => 'btn btn-danger' ,'data-toggle' => 'collapse' ,'data-target' => '#file-uploader-box')); ?>
	<div class="buttons">
		<?php echo CHtml::submitButton('ارسال',array('class' => 'btn btn-default')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->