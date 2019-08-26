<?php
/* @var $this SettingManageController */
/* @var $model SiteSetting */
?>

<div class="form">
    <?php $this->renderPartial('//partial-views/_flashMessage'); ?>

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'general-setting',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
    ));
    ?>
    <?php foreach ($model as $field) {
        if ($field->name != 'social_links'):?>
            <?php if ($field->name == 'commission'): ?>
                <div class="row">
                    <div class="row">
                        <?php echo CHtml::label($field->title, '', array('class' => 'col-lg-3 control-label')); ?>
                        <?php echo CHtml::textField("SiteSetting[$field->name]", $field->value, array('size' => 10)); ?>
                        تومان
                    </div>
                </div>
            <?php elseif ($field->name == 'sms_schedule_time'): ?>
                <div class="row">
                    <div class="row">
                        <?php echo CHtml::label($field->title, '', array('class' => 'col-lg-3 control-label')); ?>
                        <?php echo CHtml::textField("SiteSetting[$field->name]", $field->value, array('size' => 4)); ?>
                        قبل از نوبت
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="row">
                        <?php echo CHtml::label($field->title, '', array('class' => 'col-lg-3 control-label')); ?>
                        <?php echo CHtml::textarea("SiteSetting[$field->name]", $field->value, array('style' => 'width:100%', 'rows' => 4, 'class' => 'col-lg-9 form-control')); ?>
                    </div>
                </div>
            <?php endif; ?>
        <?
        endif;
    }
    ?>
    <div class="row buttons">
        <?php echo CHtml::submitButton('ذخیره', array('class' => 'btn btn-success')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>