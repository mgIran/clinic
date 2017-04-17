<?php
/* @var $this ShopPaymentController */
/* @var $model ShopPaymentMethod */

$this->breadcrumbs=array(
	'مدیریت روش های پرداخت'=>array('admin'),
	'افزودن روش پرداخت',
);

$this->menu=array(
	array('label'=>'مدیریت روش های پرداخت', 'url'=>array('admin')),
);
?>

<h1>افزودن روش پرداخت</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>