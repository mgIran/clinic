<?php
/* @var $this SettingManageController */
/* @var $model SiteSetting */
?>

<div class="form">
    <?
    $form = $this->beginWidget('CActiveForm',array(
        'id'=> 'general-setting',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
    ));
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

    <? foreach($model as $field){
        if($field->name != 'social_links'):?>
            <?php if($field->name == 'commission'): ?>
                <div class="row">
                    <div class="row">
                        <?php echo CHtml::label($field->title ,'' ,array('class' => 'col-lg-3 control-label')); ?>
                        <?php echo CHtml::textField("SiteSetting[$field->name]" ,$field->value ,array('size' => 10)); ?> تومان
                    </div>
                </div>
            <?php elseif($field->name == 'sms_schedule_time'): ?>
                <div class="row">
                    <div class="row">
                        <?php echo CHtml::label($field->title ,'' ,array('class' => 'col-lg-3 control-label')); ?>
                        <?php echo CHtml::textField("SiteSetting[$field->name]" ,$field->value ,array('size' => 4)); ?> قبل از نوبت
                    </div>
                </div>
            <?php elseif($field->name == 'sms_schedule_message'): ?>
                <div class="row">
                    <div class="row">
                        <?php echo CHtml::label($field->title ,'' ,array('class' => 'col-lg-3 control-label')); ?>
                        <?php echo CHtml::textField("SiteSetting[$field->name]" ,$field->value ,array('size' => 4)); ?> قبل از نوبت
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="row">
                        <?php echo CHtml::label($field->title ,'' ,array('class' => 'col-lg-3 control-label')); ?>
                        <?php echo CHtml::textarea("SiteSetting[$field->name]" ,$field->value ,array('size' => 60 ,'class' => 'col-lg-9 form-control')); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?
        endif;
    }
    ?>
    <div class="row buttons">
        <?php echo CHtml::submitButton('ذخیره',array('class' => 'btn btn-success')); ?>
    </div>
    <?
    $this->endWidget();
    ?>
</div>