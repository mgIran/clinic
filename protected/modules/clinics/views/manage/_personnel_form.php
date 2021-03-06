<?php
/* @var $this ClinicsManageController */
/* @var $model ClinicPersonnels */
/* @var $form CActiveForm */
?>

<div class="form row">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'clinic-personnel-form',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>false,
        'clientOptions'=>array(
            'validateOnSubmit' => true
        ),
    )); ?>
    <div class="<?= Yii::app()->user->type == 'user'?"col-lg-6 col-md-6 col-sm-6 col-xs-12":'' ?>">
        <?= $this->renderPartial('//partial-views/_flashMessage'); ?>
    </div>
    <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
    <?php
    if(!$model->isNewRecord):
        ?>
        <div class="alert alert-warning message">
            <strong>کلمه عبور:</strong>&nbsp;&nbsp;
            <span style="font-size: 18px; font-weight: 500"><?= $model->user->useGeneratedPassword()?$model->user->generatePassword():"کلمه عبور توسط کاربر تغییر یافته <a class='btn btn-xs btn-danger pull-left' href='".$this->createUrl('/clinics/manage/resetPass/'.$model->clinic_id.'/'.$model->user_id)."'>ریست پسورد</a>"; ?></span>
        </div>
        <?php
    endif;
    ?>
    </div>
    <div class="clearfix"></div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->labelEx($model,'post'); ?>
        <?php echo $form->dropDownList($model,'post', $model->getValidPosts(),array('class' => 'selectpicker')); ?>
        <?php echo $form->error($model,'post'); ?>
    </div>

    <div id="expertises" class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: none;">
        <?php echo $form->labelEx($model,'expertise'); ?>
        <select class="selectpicker" data-live-search="true" name="<?= CHtml::activeName($model,'expertise') ?>[]" multiple>
            <?php
            foreach(Expertises::model()->findAll('parent_id IS NULL') as $item):
                if($item->childes):
                ?>
                    <optgroup label="<?= CHtml::encode($item->title)?>">
                        <option value="<?= $item->id ?>"<?php
                        if($model->expertise && in_array($item->id, $model->expertise))
                            echo ' selected';
                        ?>><?= CHtml::encode($item->title)?></option>
                        <?php
                        foreach($item->childes as $child):
                            ?>
                            <option value="<?= $child->id ?>"><?= CHtml::encode($child->title)?></option>
                            <?php
                        endforeach;
                        ?>
                    </optgroup>
                <?php
                else:
                ?>
                    <option value="<?= $item->id ?>"<?php
                    if($model->expertise && in_array($item->id, $model->expertise))
                        echo ' selected';
                    ?>><?= CHtml::encode($item->title)?></option>
                <?php
                endif;
            endforeach;
            ?>
        </select>
        <?php echo $form->error($model,'expertise'); ?>
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email',array('class' => 'ltr text-right')); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->labelEx($model,'first_name'); ?>
        <?php echo $form->textField($model,'first_name'); ?>
        <?php echo $form->error($model,'first_name'); ?>
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->labelEx($model,'last_name'); ?>
        <?php echo $form->textField($model,'last_name'); ?>
        <?php echo $form->error($model,'last_name'); ?>
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->labelEx($model,'phone'); ?>
        <?php echo $form->textField($model,'phone'); ?>
        <?php echo $form->error($model,'phone'); ?>
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->labelEx($model,'mobile'); ?>
        <?php echo $form->textField($model,'mobile'); ?>
        <?php echo $form->error($model,'mobile'); ?>
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->labelEx($model,'national_code'); ?>
        <?php echo $form->textField($model,'national_code',array('maxLength' => 10, 'minLength' => 10)); ?>
        <?php echo $form->error($model,'national_code'); ?>
    </div>


    <div class="clearfix"></div>
    <div class="form-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class'=>'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?
Yii::app()->clientScript->registerCss('style','
    .form-group{
        clear:both;
    }
');
Yii::app()->clientScript->registerScript('hide-exps','
    if($("#ClinicPersonnels_post").val() == 2 || $("#ClinicPersonnels_post").val() == 3)
        $("#expertises").show();
    else
        $("#expertises").hide();
    $("body").on("change", "#ClinicPersonnels_post", function(){
        if($("#ClinicPersonnels_post").val() == 2 || $("#ClinicPersonnels_post").val() == 3)
            $("#expertises").show();
        else
            $("#expertises").hide();
    });
');