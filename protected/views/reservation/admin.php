<?php
/* @var $this ReservationController */
/* @var $model Visits */

$this->breadcrumbs=array(
	'مدیریت نوبت ها',
);
?>

<h1>مدیریت نوبت ها</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'visits-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table',
	'columns'=>array(
		array(
			'name'=>'user_id',
			'value'=>'$data->user && $data->user->userDetails ? $data->user->userDetails->getShowName() : "وجود ندارد"',
            'filter'=>CHtml::activeDropDownList($model, 'user_id', CHtml::listData(Users::model()->findAll(), 'id', 'userDetails.showName'), array('prompt'=>'همه'))
		),
        array(
			'name'=>'clinic_id',
			'value'=>'$data->clinic ? $data->clinic->clinic_name : "وجود ندارد"',
            'filter'=>CHtml::activeDropDownList($model, 'clinic_id', CHtml::listData(Clinics::model()->findAll(), 'id', 'clinic_name'), array('prompt'=>'همه'))
		),
        array(
            'name'=>'doctor_id',
            'value'=>'$data->doctor && $data->doctor->userDetails ? $data->doctor->userDetails->getShowName() : "وجود ندارد"',
            'filter'=>CHtml::activeDropDownList($model, 'doctor_id', CHtml::listData(Users::model()->findAll('role_id = 3 OR role_id = 2'), 'id', 'userDetails.showName'), array('prompt'=>'همه'))
        ),
        array(
            'name'=>'expertise_id',
            'value'=>'$data->expertise ? $data->expertise->title : "وجود ندارد"',
            'filter'=>false
        ),
        array(
            'name'=>'date',
            'value'=>'JalaliDate::date("d F Y", $data->date)',
            'filter'=>false
        ),
        array(
            'name'=>'time',
            'value'=>'$data->timeLabels[$data->time]',
            'filter'=>false
        ),
        'tracking_code',
        array(
            'name'=>'status',
            'value'=>'$data->statusLabels[$data->status]',
            'filter'=>CHtml::activeDropDownList($model, 'status', $model->statusLabels, array('prompt'=>'همه'))
        ),
		array(
			'class'=>'CButtonColumn',
			'template' => '{view} {delete}',
            'buttons'=>array(
                'view'=>array(
                    'url'=>'Yii::app()->createUrl("/reservation/view/".$data->id)'
                )
            )
		),
	),
)); ?>
