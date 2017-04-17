<?php
/* @var $this ShopPaymentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'روش های پرداخت',
);

$this->menu=array(
	array('label'=>'افزودن روش پرداخت', 'url'=>array('create')),
	array('label'=>'مدیریت روش های پرداخت', 'url'=>array('admin')),
);
?>

<h1>روش های پرداخت</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
