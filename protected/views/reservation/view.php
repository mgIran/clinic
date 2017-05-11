<?php
/* @var $this ReservationController */
/* @var $model Visits */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'جزئیات نوبت',
);

$this->menu=array(
	array('label'=>'مدیریت نوبت ها', 'url'=>array('admin')),
);
?>

<h1>جزئیات نوبت</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'user_id',
			'value'=>$model->user->userDetails->getShowName(),
		),
		array(
			'name'=>'clinic_id',
			'value'=>$model->clinic->clinic_name,
		),
		array(
			'name'=>'doctor_id',
			'value'=>$model->doctor->userDetails->getShowName(),
		),
		array(
			'name'=>'expertise_id',
			'value'=>$model->expertise->title,
		),
		array(
			'name'=>'date',
			'value'=>JalaliDate::date("d F Y", $model->date),
		),
		array(
			'name'=>'time',
			'value'=>$model->timeLabels[$model->time],
		),
		'tracking_code',
		array(
			'name'=>'check_date',
			'value'=>($model->check_date)?JalaliDate::date("d F Y", $model->check_date):'-',
		),
        array(
			'name'=>'clinic_checked_number',
			'value'=>($model->clinic_checked_number)?$model->clinic_checked_number:'-',
		),
        array(
            'name'=>'create_date',
            'value'=>JalaliDate::date("d F Y - H:i", $model->create_date),
        ),
		array(
			'name'=>'status',
			'value'=>$model->statusLabels[$model->status],
		),
	),
)); ?>
