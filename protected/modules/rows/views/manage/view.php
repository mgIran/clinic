<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->title,
);

$this->menu=array(
	array('label'=>'لیست RowsHomepage', 'url'=>array('index')),
	array('label'=>'افزودن RowsHomepage', 'url'=>array('create')),
	array('label'=>'ویرایش RowsHomepage', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'حذف RowsHomepage', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'مدیریت RowsHomepage', 'url'=>array('admin')),
);
?>

<h1>نمایش RowsHomepage #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'order',
	),
)); ?>
