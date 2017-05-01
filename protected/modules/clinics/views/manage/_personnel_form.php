<?php
/* @var $this ClinicsManageController */
/* @var $model ClinicPersonnels */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'clinic-personnel-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>false,
        'clientOptions'=>array(
            'validateOnSubmit' => true
        ),
    )); ?>

    <?= $this->renderPartial('//layouts/_flashMessage'); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'post'); ?>
        <?php echo $form->dropDownList($model,'post', $model->getValidPosts()); ?>
        <?php echo $form->error($model,'post'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class'=>'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
