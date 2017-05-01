<?php
/* @var $this ClinicsManageController */
/* @var $model ClinicPersonnels */

$this->breadcrumbs=array(
    'مطب ها' => array('admin'),
    'مدیریت پرسنل' => array('manage/adminPersonnel/'. $model->clinic_id),
    'افزودن پرسنل',
);

$this->menu=array(
    array('label'=>'لیست مطب ها', 'url'=>array('admin')),
    array('label'=>'لیست پرسنل این مطب', 'url'=>array('manage/adminPersonnel/'.$model->clinic_id)),
);
?>

<h1>ویرایش پرسنل <?= $model->user->userDetails->getFullName() ?></h1>

<?php $this->renderPartial('_personnel_form', array('model' => $model));