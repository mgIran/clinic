<?php
/* @var $this AdminsManageController */
/* @var $model Admins */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
    'پیشخوان'=> array('/admins'),
    'مدیران'=> array('/admins/manage'),
    'تغییر کلمه عبور',
);
?>
<h1>تغییر کلمه عبور</h1>
<? $this->renderPartial('//layouts/_flashMessage'); ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admins-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true
    )

)); ?>
    <div class="row form-group">
        <?php echo $form->labelEx($model,'oldPassword',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->passwordField($model,'oldPassword',array('size'=>50,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'oldPassword'); ?>
    </div>
    <div class="row form-group">
        <?php echo $form->labelEx($model,'newPassword',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->passwordField($model,'newPassword',array('size'=>50,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'newPassword'); ?>
    </div>
    <div class="row form-group">
        <?php echo $form->labelEx($model,'repeatPassword',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->passwordField($model,'repeatPassword',array('size'=>50,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'repeatPassword'); ?>
    </div>

	<div class="row form-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class' => 'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->