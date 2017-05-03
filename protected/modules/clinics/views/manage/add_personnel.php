<?php
/* @var $this ClinicsManageController */
/* @var $model ClinicPersonnels */

$this->breadcrumbs=array(
    'مطب ها' => array('admin'),
    'مدیریت پرسنل' => array('manage/adminPersonnel/'. $model->clinic_id),
    'افزودن پرسنل',
);

$criteria = new CDbCriteria();
$criteria->condition= 't.id NOT IN (SELECT `clinicPersonnels`.user_id FROM {{clinic_personnels}} `clinicPersonnels` WHERE clinicPersonnels.clinic_id = :clinic_id)';
$criteria->params = array(':clinic_id' => $model->clinic_id);
$validUsers = CHtml::listData(Users::model()->findAll($criteria), 'id', 'userDetails.showName');
if(!$validUsers)
    $this->redirect(array('manage/addNewPersonnel/'.$model->clinic_id));
$this->menu=array(
    array('label'=>'لیست مطب ها', 'url'=>array('admin')),
    array('label'=>'لیست پرسنل این مطب', 'url'=>array('manage/adminPersonnel/'.$model->clinic_id)),
);
?>

<h1>افزودن پرسنل از کاربران</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'clinic-personnel-form',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'clientOptions'=>array(
        'validateOnSubmit' => true
    ),
)); ?>

<?= $this->renderPartial('//partial-views/_flashMessage'); ?>

<div class="form-group">
    <?php echo $form->labelEx($model,'user_id'); ?>
    <?php echo $form->dropDownList($model, 'user_id', $validUsers,array(
            'class' => 'selectpicker',
            'data-live-search' => true,
            'prompt' => 'کاربر موردنظر را انتخاب کنید'
        )); ?>
    <?php echo $form->error($model,'user_id'); ?>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model,'post'); ?>
    <?php echo $form->dropDownList($model,'post', $model->getValidPosts()); ?>
    <?php echo $form->error($model,'post'); ?>
</div>

<div class="form-group buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'ثبت' : 'ذخیره', array('class'=>'btn btn-success')); ?>
    <?php echo CHtml::link('ایجاد پرسنل جدید', array('manage/addNewPersonnel/'.$model->clinic_id), array('class'=>'btn btn-primary')); ?>
</div>

<?php $this->endWidget(); ?>
</div>