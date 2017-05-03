<?php
/* @var $this ClinicsManageController */
/* @var $model Clinics */

$this->breadcrumbs=array(
	'مطب ها'=>array('admin'),
	$model->clinic_name => array('view','id' => $model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن مطب', 'url'=>array('create')),
	array('label'=>'لیست مطب ها', 'url'=>array('admin')),
);
?>

<h1>ویرایش <?php echo $model->clinic_name;?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>