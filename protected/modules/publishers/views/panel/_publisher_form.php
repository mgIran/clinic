<?php
/* @var $this PublishersPanelController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'publishers-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true
        )
    )); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email'); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <?php if($model->isNewRecord):?>
        <div class="row">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password'); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
    <?php endif;?>

    <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList($model, 'status', $model->statusLabels, array('options' => array('active'=>array('selected'=>true)))); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>
    <?php
    if($model->isNewRecord):
    ?>
    <div class="row">
        <?php echo CHtml::label('نوع کاربری','type'); ?>
        <?php echo $form->dropDownList($model, 'type', UserDetails::model()->typeLabels, array('real' => 'حقیقی', 'legal' => 'حقوقی')); ?>
    </div>
    <?php
    endif;
    ?>
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش' ,array('class' => 'btn btn-success')); ?>
    </div>

    <?php  $this->endWidget(); ?>

</div><!-- form -->
