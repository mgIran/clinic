<?php
/* @var $this TownsManageController */
/* @var $model Towns */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>افزودن استان</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>