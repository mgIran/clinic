<?php
if(Yii::app()->user->hasFlash('failed'))
    echo '<div class=\'alert alert-danger fade in\'>
            <button class=\'close close-sm\' type=\'button\' data-dismiss=\'alert\'><i class=\'icon-remove\'></i></button>
            '.Yii::app()->user->getFlash('failed').'
        </div>';
?>
<?php
/* @var $this TownsManageController */
/* @var $model Towns */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'towns-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">فیلد های دارای <span class="required">*</span> الزامی هستند.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'slug'); ?>
		<?php echo $form->textField($model,'slug',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'slug'); ?>
        <span class="desc">انگلیسی وارد شود</span>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'tags'); ?>

        <?php
        $this->widget("ext.tagIt.tagIt",array(
            'model' => $model,
            'attribute' => 'tags',
            'suggestType' => 'json',
            'suggestUrl' => Yii::app()->createUrl('/advertises/tags/list'),
            'data' => $model->tags,
            'placeholder' => 'تایپ کنید و کلید Enter را بزنید یا از لیست انتخاب کنید ...'
        ));
        Yii::app()->clientScript->registerCss('tag-it' ,'
            ul.tagit li.tagit-new{
                width:300px;
            }
        ');
        ?>
        <?php echo $form->error($model,'tags'); ?>
    </div>


    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'افزودن' : 'ویرایش',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->