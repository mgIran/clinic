<?php
/* @var $this TicketMessagesController */
/* @var $model TicketMessages */
/* @var $ticket Tickets */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ticket-messages-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions' => array(
		'validateOnSubmit' => true
	),
	'action' => array('/tickets/manage/send')
)); ?>
	<?php
	if(isset($ticket)):
	?>
		<div class="form-group">
			<?php echo CHtml::hiddenField('TicketMessages[ticket_id]',$ticket->id); ?>
		</div>
	<?php
	endif;
	?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50 ,'class' => 'form-control')); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div id="file-uploader-box" class="form-group collapse" >
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
                if(responseObj.state == "ok")
                {
                    {serverName} = responseObj.fileName;
                    $(".submit-image-warning").addClass("hidden");
                }else if(responseObj.state == "error"){
                    console.log(responseObj.msg);
                }
            ',
		));
		?>
		<?php echo $form->error($model,'attachment'); ?>
	</div>

	<?php echo CHtml::button('فایل ضمیمه',array('class' => 'btn btn-danger' ,'data-toggle' => 'collapse' ,'data-target' => '#file-uploader-box')); ?>
	<div class="buttons">
		<?php echo CHtml::submitButton('ارسال' , array('class' => 'btn btn-default')); ?>
	</div>
	<div class="form-group buttons col-lg-12 col-md-12 col-sm-12 col-xs-12">

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->