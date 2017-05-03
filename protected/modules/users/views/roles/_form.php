<?php
/* @var $this UsersRolesController */
/* @var $model UserRoles */
/* @var $form CActiveForm */
/* @var $actions array */
Yii::app()->clientScript->registerScript('resetForm','document.getElementById("admin-roles-form").reset();');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admin-roles-form',
	'enableAjaxValidation'=>true,

)); ?>
    <?php $this->renderPartial('//partial-views/_flashMessage');?>
	<p class="note">فیلد های دارای <span class="required">*</span> الزامی هستند .</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row form-group">
		<?php echo $form->labelEx($model,'name' ,array('class'=>'col-lg-2 control-label')); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

    <div class="row form-group">
        <?php echo $form->labelEx($model,'role',array('class'=>'col-lg-2 control-label')); ?>
        <?php echo $form->textField($model,'role',array('size'=>50,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'role'); ?>
    </div>

	<div class="row form-group">
        <?php echo $form->labelEx($model,'permissions',array('class'=>'col-lg-2 control-label')); ?>
        <?php $this->widget('application.extensions.jsTree.jsTree', array(
            'name' => 'UserRoles[permissions]',
            'data' => $actions,
            'selected' => $model->permissions
        )); ?>
        <?php echo $form->error($model,'permissions'); ?>
    </div>

	<div class="row form-group buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش', array('class'=>'btn btn-success'));?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->