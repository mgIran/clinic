<?php
/* @var $this ShopShippingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'روش های تحویل',
);

$this->menu=array(
	array('label'=>'افزودن روش تحویل', 'url'=>array('create')),
	array('label'=>'مدیریت روش های تحویل', 'url'=>array('admin')),
);
?>

<h1>روش های تحویل</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
