<?php
/* @var $this TicketsManageController */
/* @var $model Tickets */
/* @var $form CActiveForm */
?>

<div class="search-form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>$this->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'subject'); ?>
		<?php echo $form->textField($model,'subject'); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'department_id'); ?>
		<?php echo $form->dropDownList($model,'department_id',CHtml::listData(TicketDepartments::model()->findAll(),'id','title'),array('prompt' => 'همه')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',array(
			'waiting' => $model->statusLabels['waiting'],
			'pending' => $model->statusLabels['pending'],
			'open' => $model->statusLabels['open'],
			'close' => $model->statusLabels['close'],
		)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('جستجو',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->