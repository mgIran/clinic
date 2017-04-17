<?php
/* @var $this ShopShippingController */
/* @var $model ShopShippingMethod */

$this->breadcrumbs=array(
	'مدیریت روش های تحویل'=>array('admin'),
	$model->title,
);

$this->menu=array(
	array('label'=>'لیست روش های تحویل', 'url'=>array('index')),
	array('label'=>'افزودن روش تحویل', 'url'=>array('create')),
	array('label'=>'ویرایش روش تحویل', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف روش تحویل', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت روش های تحویل', 'url'=>array('admin')),
);
?>

<h1>نمایش روش تحویل "<?php echo $model->title; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'description',
		'price',
	),
)); ?>
