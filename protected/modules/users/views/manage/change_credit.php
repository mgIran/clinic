<?php
/* @var $this UsersManageController */
/* @var $model UserDetails */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);
if($model->user->role_id = 2)
	$adminLink = array('label'=>'لیست ناشران', 'url'=>array('adminPublishers'));
else
	$adminLink = array('label'=>'لیست کاربران', 'url'=>array('/users/manage'));
$this->menu=array(
	$adminLink,
);
?>

<h1>تغییر اعتبار کاربر "<?= $model->fa_name ?>"</h1>

<? $this->renderPartial('//partial-views/_flashMessage'); ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo CHtml::label('نام و نام خانوادگی',''); ?>
		<strong><?php echo CHtml::label($model->fa_name,''); ?></strong>
	</div>
	<div class="row">
		<?php echo CHtml::label('اعتبار فعلی',''); ?>
		<strong><?php echo CHtml::label(Controller::parseNumbers(number_format($model->credit)),''); ?>&nbsp;تومان</strong>
	</div>

	<div class="row">
        <?php echo CHtml::label('اعتبار جدید *',''); ?>
		<?php echo CHtml::textField('UserDetails[credit]',$model->credit,array('size'=>60,'maxlength'=>11)); ?>تومان
		<?php echo $form->error($model,'credit'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('ذخیره',array('class' => 'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->