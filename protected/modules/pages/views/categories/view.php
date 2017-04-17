<?php
/* @var $this PageCategoriesManageController */
/* @var $model PageCategories */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'لیست PageCategories', 'url'=>array('index')),
	array('label'=>'افزودن PageCategories', 'url'=>array('create')),
	array('label'=>'ویرایش PageCategories', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف PageCategories', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت PageCategories', 'url'=>array('admin')),
);
?>

<h1>نمایش PageCategories #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'slug',
	),
)); ?>
