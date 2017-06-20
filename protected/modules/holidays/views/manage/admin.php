<?php
/* @var $this HolidaysMangeController */
/* @var $model Holidays */

$this->breadcrumbs=array(
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن تعطیلات جدید', 'url'=>array('create')),
);
?>

<h1>مدیریت تعطیلات</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'holidays-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
		'title',
		array(
			'name' => 'date',
			'value' => function($data){
				return JalaliDate::date('l d F Y', $data->date);
			}
		),
		array(
			'class'=>'CButtonColumn',
			'template' => '{update} {delete}'
		),
	),
)); ?>
