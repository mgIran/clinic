<?php
/* @var $this ClinicsManageController */
/* @var $model Clinics */

$this->breadcrumbs=array(
	'مطب ها',
	'مدیریت',
);

$this->menu=array(
	array('label'=>'افزودن مطب', 'url'=>array('create')),
);
?>

<h1>مدیریت مطب ها</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'clinics-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
		'clinic_name',
		array(
			'name'=>'town_id',
			'value'=>'$data->town->name',
            'filter'=>CHtml::activeDropDownList($model, 'town_id', CHtml::listData(Towns::model()->findAll(), 'id', 'name'), array('prompt'=>'همه'))
		),
        array(
			'name'=>'place_id',
			'value'=>'$data->place->name',
            'filter'=>CHtml::activeDropDownList($model, 'place_id', CHtml::listData(Places::model()->findAll(), 'id', 'name'), array('prompt'=>'همه'))
		),
		'address',
		array(
			'class'=>'CButtonColumn',
            'template'=>'{addPersonnel} {view} {update} {delete}',
			'buttons' =>array(
				'addPersonnel'=>array(
					'imageUrl' => Yii::app()->theme->baseUrl."/img/new-user.svg",
					'options' => array('class' => 'add-person'),
					'url'=>'Yii::app()->controller->createUrl("manage/adminPersonnel/".$data->id)',
					'label'=>'لیست پرسنل'
				)
			)
		),
	),
)); ?>
<style>
	.add-person img{
		display: block;
		overflow: hidden;
		width: 100%;
	}
	.add-person{
		display:inline-block; width:15px; height: 15px;vertical-align: middle;
	}
</style>