<?php
/* @var $this AdminsManageController */
/* @var $dataProvider Admins */

$this->breadcrumbs=array(
		'پشتیبانی',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#admins-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>پشتیبانی</h1>
<div class="well">
<?php $this->renderPartial("_search",array('model' => new Tickets())); ?>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'admins-grid',
	'dataProvider'=>$dataProvider,
	'rowCssClassExpression' => '$data->getCssClass()',
	'itemsCssClass'=>'table',
	'columns'=>array(
		array(
			'value' => function($data){
				$criteria = new CDbCriteria();
				$criteria->compare('visit',0);
				$criteria->compare('ticket_id',$data->id);
				$criteria->compare('sender','user');
				return TicketMessages::model()->count($criteria)?'<div class="text-center"><span class="icon icon-envelope"></span></div>':'';
			},
			'type' => 'html'
		),
		'code',
		'subject',
		array(
			'name' => 'department_id' ,
			'value' => '$data->department->title'
		),
		array(
				'name' => 'status' ,
				'value' => '$data->statusLabels[$data->status]'
		),
		array(
			'class'=>'CButtonColumn',
			'template' => '{view}{delete}'
		),
	),
)); ?>
