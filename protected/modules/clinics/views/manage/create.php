<?php
/* @var $this ClinicsManageController */
/* @var $model Clinics */
/* @var $towns array */

$this->breadcrumbs=array(
	'مطب ها'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'لیست مطب ها', 'url'=>array('admin')),
);
?>

<h1>افزودن مطب</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'towns'=>$towns)); ?>