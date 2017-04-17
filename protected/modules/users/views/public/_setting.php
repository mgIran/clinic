<?php
/* @var $this PublicController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<?php if(Yii::app()->user->hasFlash('success')):?>
    <div class="alert alert-success fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('success');?>
    </div>
<?php elseif(Yii::app()->user->hasFlash('failed')):?>
    <div class="alert alert-danger fade in">
        <button class="close close-sm" type="button" data-dismiss="alert"><i class="icon-remove"></i></button>
        <?php echo Yii::app()->user->getFlash('failed');?>
    </div>
<?php endif;?>

<div class="col-md-6">
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

        <div class="form-group">
            <?php echo $form->passwordField($model,'oldPassword',array('placeholder'=>$model->getAttributeLabel('oldPassword').' *','class'=>'form-control','maxlength'=>100)); ?>
            <?php echo $form->error($model,'oldPassword'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->passwordField($model,'newPassword',array('placeholder'=>$model->getAttributeLabel('newPassword').' *','class'=>'form-control','maxlength'=>100)); ?>
            <?php echo $form->error($model,'newPassword'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->passwordField($model,'repeatPassword',array('placeholder'=>$model->getAttributeLabel('repeatPassword').' *','class'=>'form-control','maxlength'=>100)); ?>
            <?php echo $form->error($model,'repeatPassword'); ?>
        </div>

        <div class="buttons">
            <?php echo CHtml::submitButton('تغییر کلمه عبور',array('class'=>'btn btn-success')); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>