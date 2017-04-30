<?php
/* @var $this UsersPublicController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="white-form">
    <h3>تغییر کلمه عبور</h3>
    <p class="description">جهت تغییر کلمه عبور خود فرم زیر را پر کنید.</p>

    <?php $this->renderPartial('//partial-views/_flashMessage');?>

    <div class="form">

        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'users-form',
            'action' => Yii::app()->createUrl('/users/public/setting'),
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation'=>true,
        )); ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?php echo $form->passwordField($model,'oldPassword',array('placeholder'=>$model->getAttributeLabel('oldPassword').' *','class'=>'form-control','maxlength'=>100,'value'=>'')); ?>
                <?php echo $form->error($model,'oldPassword'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?php echo $form->passwordField($model,'newPassword',array('placeholder'=>$model->getAttributeLabel('newPassword').' *','class'=>'form-control','maxlength'=>100,'value'=>'')); ?>
                <?php echo $form->error($model,'newPassword'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?php echo $form->passwordField($model,'repeatPassword',array('placeholder'=>$model->getAttributeLabel('repeatPassword').' *','class'=>'form-control','maxlength'=>100,'value'=>'')); ?>
                <?php echo $form->error($model,'repeatPassword'); ?>
            </div>
        </div>

        <div class="buttons">
            <?php echo CHtml::submitButton('تغییر کلمه عبور',array('class'=>'btn btn-success')); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div>

</div>
