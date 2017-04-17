<?php
/* @var $this UserBonsManageController */
/* @var $model UserBons */

$this->breadcrumbs=array(
	'مدیریت بن های خرید'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت بن های خرید', 'url'=>array('admin')),
);
?>

<h1>افزودن بن خرید</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>