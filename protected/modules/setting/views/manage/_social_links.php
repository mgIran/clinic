<?php
/* @var $this SettingManageController */
/* @var $model SiteSetting */
$social_links = false;
if ($model->value)
    $social_links = CJSON::decode($model->value);
?>

<div class="form">
    <?php $this->renderPartial('//partial-views/_flashMessage'); ?>

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'general-setting',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
    ));
    ?>
    <div class="row">
        <div class="row">
            <?php echo CHtml::label('لینک فیسبوک', '', array('class' => 'col-lg-3 control-label')); ?>
            <?php echo CHtml::textField("SiteSetting[social_links][facebook]", ($social_links && isset($social_links['facebook']) ? $social_links['facebook'] : ''), array('size' => 60, 'class' => 'col-lg-9 form-control text-left ltr')); ?>
        </div>
    </div>
    <div class="row">
        <div class="row">
            <?php echo CHtml::label('لینک اینستاگرام', '', array('class' => 'col-lg-3 control-label')); ?>
            <?php echo CHtml::textField("SiteSetting[social_links][instagram]", ($social_links && isset($social_links['instagram']) ? $social_links['instagram'] : ''), array('size' => 60, 'class' => 'col-lg-9 form-control text-left ltr')); ?>
        </div>
    </div>
    <div class="row">
        <div class="row">
            <?php echo CHtml::label('لینک تلگرام', '', array('class' => 'col-lg-3 control-label')); ?>
            <?php echo CHtml::textField("SiteSetting[social_links][telegram]", ($social_links && isset($social_links['telegram']) ? $social_links['telegram'] : ''), array('size' => 60, 'class' => 'col-lg-9 form-control text-left ltr')); ?>
        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('ذخیره', array('class' => 'btn btn-success')); ?>
    </div>
    <?
    $this->endWidget();
    ?>
</div>