<?php
/* @var $this UsersExpertisesController */
/* @var $model Expertises */

$this->breadcrumbs=array(
	'تخصص ها'=>array('index'),
	'لیست تخصص ها',
);

$this->menu=array(
	array('label'=>'افزودن تخصص', 'url'=>array('create')),
);
?>

<h1>لیست تخصص ها</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'expertises-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
		'title',
		array(
			'class'=>'CButtonColumn',
            'template'=>'{update} {delete}'
		),
	),
)); ?>
