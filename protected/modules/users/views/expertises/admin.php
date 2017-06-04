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
			'name' => 'parent_id',
			'value' => '$data->parent?$data->parent->title:null',
			'filter' => CHtml::listData(Expertises::model()->findAll('parent_id IS NULL'),'id','title')
		),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{update} {delete}'
		),
	),
)); ?>
