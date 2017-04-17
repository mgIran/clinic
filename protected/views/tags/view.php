<?php
/* @var $this TagsManageController */
/* @var $model Tags */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->title,
);

$this->menu=array(
	array('label'=>'لیست Tags', 'url'=>array('index')),
	array('label'=>'افزودن Tags', 'url'=>array('create')),
	array('label'=>'ویرایش Tags', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف Tags', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت Tags', 'url'=>array('admin')),
);
?>

<h1>نمایش Tags #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
	),
)); ?>
