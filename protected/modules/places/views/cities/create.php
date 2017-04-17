<?php
/* @var $this PlacesManageController */
/* @var $model Places */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>افزودن شهر</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>