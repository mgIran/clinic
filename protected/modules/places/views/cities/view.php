<?php
/* @var $this PlacesManageController */
/* @var $model Places */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'لیست Places', 'url'=>array('index')),
	array('label'=>'افزودن Places', 'url'=>array('create')),
	array('label'=>'ویرایش Places', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف Places', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت Places', 'url'=>array('admin')),
);
?>

<h1>نمایش Places #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'town_id',
	),
)); ?>
