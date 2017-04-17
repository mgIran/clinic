<?php
/* @var $this ShopShippingController */
/* @var $model ShopShippingMethod */

$this->breadcrumbs=array(
	'مدیریت روش های تحویل'=>array('admin'),
	'افزودن روش تحویل',
);

$this->menu=array(
	array('label'=>'مدیریت روش های تحویل', 'url'=>array('admin')),
);
?>

<h1>افزودن روش تحویل</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>