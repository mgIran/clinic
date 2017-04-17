<?php
/* @var $this UserBonsManageController */
/* @var $model UserBons */
/* @var $dataProvider CArrayDataProvider */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>'لیست بن های خرید', 'url'=>array('admin')),
	array('label'=>'افزودن بن خرید', 'url'=>array('create')),
	array('label'=>'ویرایش بن خرید', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'غیرفعال سازی بن', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از غیر فعال سازی این بن اطمینان دارید؟')),
);
?>

<h1>نمایش بن خرید با کد "<?php echo $model->code; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'title',
		'code',
		array(
			'name' => 'amount',
			'value' => Controller::parseNumbers(number_format($model->amount)).' تومان'
		),
		array(
			'name' => 'start_date',
			'value' => JalaliDate::date('Y/m/d',$model->start_date)
		),
		array(
			'name' => 'end_date',
			'value' => JalaliDate::date('Y/m/d',$model->end_date)
		),
		array(
			'name' => 'user_limit',
			'value' => Controller::parseNumbers(number_format($model->user_limit)).' نفر'
		),
		array(
			'label' => 'تعداد استفاده شده',
			'value' => Controller::parseNumbers(number_format($model->numberUsed)).' کاربر',
		),
		array(
			'label' => 'جمع اعتبار اعمال شده',
			'value' => Controller::parseNumbers(number_format($model->sumAmountUsed))." تومان",
		),
		array(
			'name' => 'status',
			'value' => $model->getStatusLabel()
		)
	),
)); ?>

<h2>لیست کاربرانی که از این بن استفاده کرده اند</h2>
<?php
$relModel = new UserBonRel();
$relModel->bon_id = $model->id;
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-bons-grid',
	'dataProvider'=>$relModel->search(),
	'columns'=>array(
		array(
			'header'=>'نام کاربر',
			'name' => 'user.userDetails.showName',
		),
		array(
			'name' => 'date',
			'value' => 'JalaliDate::date("Y/m/d",$data->date)',
			'filter' => false
		),
		array(
			'name' => 'amount',
			'value' => 'Controller::parseNumbers(number_format($data->amount))." تومان"',
		),
	),
)); ?>