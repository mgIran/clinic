<?php
/* @var $this ShopPaymentController */
/* @var $model ShopPaymentMethod */

$this->breadcrumbs=array(
	'مدیریت روش های پرداخت'=>array('admin'),
	$model->title,
);

$this->menu=array(
	array('label'=>'لیست روش پرداخت', 'url'=>array('index')),
	array('label'=>'افزودن روش پرداخت', 'url'=>array('create')),
	array('label'=>'ویرایش روش پرداخت', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف روش پرداخت', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت روش پرداخت', 'url'=>array('admin')),
);
?>

<h1>نمایش روش پرداخت "<?php echo $model->title; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'description',
		'price',
	),
)); ?>
