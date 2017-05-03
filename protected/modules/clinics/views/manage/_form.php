<?php
/* @var $this ClinicsManageController */
/* @var $model Clinics */
/* @var $form CActiveForm */
/* @var $towns array */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'clinics-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

    <?= $this->renderPartial('//partial-views/_flashMessage'); ?>

	<p class="note">فیلد های <span class="required">*</span>دار اجباری هستند.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'clinic_name'); ?>
		<?php echo $form->textField($model,'clinic_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'clinic_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'town_id'); ?>
        <?php $this->widget('ext.dropDown.dropDown', array(
            'id' => 'towns',
            'model'=>$model,
            'attribute' => 'town_id',
            'label' => 'استان مورد نظر را انتخاب کنید',
            'emptyOpt' => false,
            'data' => CHtml::listData(Towns::model()->findAll() , 'id' ,'name'),
            'caret' => '<i class="icon icon-chevron-down"></i>',
            'selected' => $model->town_id?$model->town_id:false,
            'containerClass'=>'dropdown',
            'onclickAjax' => array(
                'url' => Yii::app()->createUrl('/places/cities/getCities'),
                'type' => 'GET',
                'dataType' => 'html',
                'success' => '
                    $("#places-label").html("شهرستان مورد نظر را انتخاب کنید");
                    $("#places").html(data);
                    $("#places-hidden").val("");'
            )
        )); ?>
		<?php echo $form->error($model,'town_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'place_id'); ?>
        <?php $this->widget('ext.dropDown.dropDown', array(
            'id' => 'places',
            'model'=>$model,
            'attribute' => 'place_id',
            'label' => 'شهرستان مورد نظر را انتخاب کنید',
            'selected' => $model->place_id?$model->place_id:false,
            'containerClass'=>'dropdown',
            'data' => $model->town_id?CHtml::listData(Places::model()->findAll('town_id = :id' ,array(':id'=>$model->town_id)) , 'id' ,'name'):null,
            'caret' => '<i class="icon icon-chevron-down"></i>',
        )); ?>
		<?php echo $form->error($model,'place_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zip_code'); ?>
		<?php echo $form->textField($model,'zip_code',array('maxlength'=>10)); ?>
		<?php echo $form->error($model,'zip_code'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'phone'); ?>
        <?php echo $form->textField($model,'phone',array('maxlength'=>11)); ?>
        <?php echo $form->error($model,'phone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'fax'); ?>
        <?php echo $form->textField($model,'fax',array('maxlength'=>11)); ?>
        <?php echo $form->error($model,'fax'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>30, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>30, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'contracts'); ?>
        <div style="display: inline-block;vertical-align: top;">
            <?php $model->setIsNewRecord(false); $this->widget('application.extensions.dynamicField.dynamicField', array(
                'id'=>'dynamic-field',
                'model'=>$model,
                'attributeName'=>'contracts',
                'attributeValue'=>CJSON::decode($model->contracts),
                'inputType'=>'textField',
            ));?>
        </div>
		<?php echo $form->error($model,'contracts'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->