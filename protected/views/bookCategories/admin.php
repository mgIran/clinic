<?php
/* @var $this BookCategoriesController */
/* @var $model BookCategories */

$this->breadcrumbs=array(
	'دسته بندی های کتاب',
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
);

?>

<h1>مدیریت دسته بندی ها</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'book-categories-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
		'title',
		array(
			'header' => 'والد',
			'name' => 'parent_id',
			'value' => '$data->parent?$data->parent->title:""',
			'filter' => BookCategories::model()->adminSortList(null,false)
		),
		array(
			'class'=>'CButtonColumn',
			'template' => '{update}{delete}'
		),
	),
)); ?>
