<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت ردیف های ثابت کتاب',
);
?>

<h1>مدیریت ردیف های کتاب</h1>
<? $this->renderPartial('//layouts/_flashMessage'); ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'rows-homepage-grid',
	'dataProvider'=>$model->search(true),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
		'title',
		array(
			'name' => 'status',
			'value' => '$data->statusLabel',
			'filter' => $model->statusLabels
		),
		array(
			'class'=>'CButtonColumn',
            'template' => '{update}',
			'buttons' => array(
				'update' => array(
					'url' => 'Yii::app()->createUrl("/rows/manage/updateConst",array("id" => $data->id))'
				)
			)
		),
	),
)); ?>
