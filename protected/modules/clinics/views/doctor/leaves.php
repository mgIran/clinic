<?php
/* @var $this ClinicsDoctorController */
/* @var $model DoctorLeaves */
/* @var $search DoctorLeaves */
/* @var $form CActiveForm */
?>

<h3>مدیریت برنامه زمانی مرخصی ها</h3>
<p class="description">جهت تعیین برنامه زمانی مرخص های خود در این کلینیک، لطفا تاریخ موردنظر را به جدول اضافه کنید.</p>
<?php $this->renderPartial('//partial-views/_flashMessage') ?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'doctor-schedules',
        'enableAjaxValidation' => false
    ));
    ?>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'date'); ?>
        <?php echo $form->tex($model, 'date'); ?>
        <?php echo $form->labelEx($model, 'date'); ?>
    </div>
    <?php echo CHtml::submitButton('ثبت / ویرایش',array('class' => 'btn btn-success')) ?>
    <?php
    $this->endWidget();
    ?>
</div>