<?php
/* @var $this ClinicsManageController */
/* @var $model ClinicPersonnels */
/* @var $form CActiveForm */
?>

<h3>تخصص های پزشکی <?= $model->user->userDetails->getShowName() ?></h3>
<p class="description">لطفا تخصص های پزشکی موردنظر را از لیست زیر علامت گذاری کرده و ذخیره نمایید.</p>
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
    <div class="clearfix"></div>
    <div id="expertises" class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php echo $form->labelEx($model,'expertise'); ?>
        <div class="clearfix"></div>
        <select class="selectpicker" data-live-search="true" name="<?= CHtml::activeName($model,'expertise') ?>[]" multiple>
            <?php
            foreach(Expertises::model()->findAll('parent_id IS NULL') as $item):
                if($item->childes):
                    ?>
                    <optgroup label="<?= CHtml::encode($item->title)?>">
                        <option value="<?= $item->id ?>"<?php
                        if(in_array($item->id, $model->expertise))
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
                    if(in_array($item->id, $model->expertise))
                        echo ' selected';
                    ?>><?= CHtml::encode($item->title)?></option>
                    <?php
                endif;
            endforeach;
            ?>
        </select>
        <?php echo $form->error($model,'expertise'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="form-group buttons">
        <?php echo CHtml::submitButton('ذخیره', array('class'=>'btn btn-success')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->