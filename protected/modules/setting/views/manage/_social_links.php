<?php
/* @var $this SiteSettingManageController */
/* @var $model SiteSetting */
$social_links = false;
if($model->value)
    $social_links = CJSON::decode($model->value);
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

    <div class="row">
        <div class="row">
            <?php echo CHtml::label('لینک فیسبوک','',array('class'=>'col-lg-3 control-label')); ?>
            <?php echo CHtml::textField("SiteSetting[social_links][facebook]",($social_links && isset($social_links['facebook'])?$social_links['facebook']:''),array('size'=>60,'class'=>'col-lg-9 form-control text-left ltr')); ?>
        </div>
    </div>
    <div class="row">
        <div class="row">
            <?php echo CHtml::label('لینک توییتر','',array('class'=>'col-lg-3 control-label')); ?>
            <?php echo CHtml::textField("SiteSetting[social_links][twitter]",($social_links && isset($social_links['twitter'])?$social_links['twitter']:''),array('size'=>60,'class'=>'col-lg-9 form-control text-left ltr')); ?>
        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('ذخیره',array('class' => 'btn btn-success')); ?>
    </div>
    <?
    $this->endWidget();
    ?>
</div>