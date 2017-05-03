<?php
/* @var $this ClassTagsManageController */
/* @var $model ClassTags */

$this->breadcrumbs=array(
	'مدیریت برچسب ها',
);

$this->menu=array(
	array('label'=>'افزودن برچسب', 'url'=>array('create')),
);
?>
<? $this->renderPartial('//partial-views/_flashMessage'); ?>
<h1>مدیریت برچسب ها</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'class-tags-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
		'title',
		array(
			'class'=>'CButtonColumn',
			'template' => '{delete}'
		),
	),
)); ?>
