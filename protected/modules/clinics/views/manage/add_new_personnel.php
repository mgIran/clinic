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

<h3>افزودن پرسنل</h3>
    <p class="description">جهت افزودن پرسنل جدید فرم زیر را پر کنید.</p>
<?php $this->renderPartial('_personnel_form', array('model' => $model));