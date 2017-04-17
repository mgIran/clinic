<?php
/* @var $this RowsManageController */
/* @var $model RowsHomepage */

$this->breadcrumbs=array(
	'مدیریت ردیف های دلخواه کتاب',
);

$this->menu=array(
	array('label'=>'افزودن ردیف', 'url'=>array('create')),
);
?>

<h1>مدیریت ردیف های کتاب</h1>
<? $this->renderPartial('//layouts/_flashMessage'); ?>
<div class="description">** می توانید با جابجا کردن سطرها ردیف ها را مرتب سازی کنید.</div>
<?php $this->widget('ext.yiiSortableModel.widgets.SortableCGridView', array(
	'orderField' => 'order',
	'idField' => 'id',
	'orderUrl' => 'order',
	'id'=>'rows-homepage-grid',
	'dataProvider'=>$model->search(),
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
            'template' => '{update} {delete}',
			'buttons' =>array(
				'delete' =>array(
					'visible' => '!$data->query'
				)
			)
		),
	),
)); ?>
