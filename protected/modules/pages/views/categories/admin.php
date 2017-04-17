<?php
/* @var $this PageCategoriesManageController */
/* @var $model PageCategories */

$this->breadcrumbs=array(
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن PageCategories', 'url'=>array('create')),
);
?>

<h1>مدیریت Page Categories</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'page-categories-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'slug',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
