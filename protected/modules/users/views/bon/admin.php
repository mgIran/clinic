<?php
/* @var $this UserBonsManageController */
/* @var $model UserBons */

$this->breadcrumbs=array(
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن بن خرید', 'url'=>array('create')),
);
?>

<h1>مدیریت بن های خرید</h1>
<?php
$this->renderPartial('//layouts/_flashMessage');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-bons-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
		'title',
		'code',
		array(
			'name' => 'amount',
			'value' => 'Controller::parseNumbers(number_format($data->amount))." تومان"',
		),
		array(
			'name' => 'end_date',
			'value' => 'JalaliDate::date("Y/m/d",$data->end_date)',
			'filter' => false
		),
		array(
			'name' => 'user_limit',
			'value' => '$data->user_limit?Controller::parseNumbers(number_format($data->user_limit))." نفر":"ندارد"',
			'filter' => false
		),
		array(
			'header' => 'تعداد استفاده شده',
			'value' => 'Controller::parseNumbers(number_format($data->numberUsed))',
			'filter' => false
		),
		array(
			'header' => 'جمع اعتبار اعمال شده',
			'value' => 'Controller::parseNumbers(number_format($data->sumAmountUsed))." تومان"',
			'filter' => false
		),
		array(
			'name' => 'status',
			'value' => '$data->statusLabel',
			'filter' => $model->statusLabels
		),
		array(
			'class'=>'CButtonColumn',
			'template' => '{view} {update}'
		),
	),
)); ?>
