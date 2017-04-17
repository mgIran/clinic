<?php
/* @var $this TownsManageController */
/* @var $model Towns */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'لیست Towns', 'url'=>array('index')),
	array('label'=>'افزودن Towns', 'url'=>array('create')),
	array('label'=>'ویرایش Towns', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف Towns', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت Towns', 'url'=>array('admin')),
);
?>

<h1>نمایش Towns #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
