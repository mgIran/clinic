<?php
/* @var $this ShopOrderController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Shop Orders',
);

$this->menu=array(
	array('label'=>'افزودن ', 'url'=>array('create')),
	array('label'=>'مدیریت ', 'url'=>array('admin')),
);
?>

<h1>Shop Orders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
