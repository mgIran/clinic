<?php
/* @var $this UsersManageController */
/* @var $model Users */

Yii::app()->clientScript->registerCss('imgSize','
.national-card-image
{
	max-width:500px;
	max-height:500px;
}
');

$this->breadcrumbs=array(
	'کاربران'=>array('index'),
	$model->userDetails->getShowName() && !empty($model->userDetails->getShowName())?$model->userDetails->getShowName():$model->email,
);

	$this->menu=array(
        array('label'=>'تایید کاربر', 'url'=>'#', 'linkOptions'=>array('submit'=>array('approve','id'=>$model->id),'confirm'=>'آیا از تایید کاربر اطمینان دارید؟'),'visible' => '$data->status != "active"'),
        array('label'=>'حذف کاربر', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از حذف کاربر اطمینان دارید؟')),
        array('label'=>'مدیرت کاربران', 'url'=>array($model->role_id == 2?'adminPublishers':'admin')),
    );
?>

<h1>نمایش اطلاعات <?php echo $model->userDetails->getShowName() && !empty($model->userDetails->getShowName())?$model->userDetails->getShowName():$model->email; ?></h1>
<? $this->renderPartial('//partial-views/_flashMessage') ?>
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'نام و نام خانوادگی',
				'value'=>$model->userDetails->getShowName(),
			),
			array(
				'name'=>'شماره موبایل',
				'value'=>$model->userDetails->mobile,
			),
			array(
				'name'=>'شماره ثابت',
				'value'=>$model->userDetails->phone,
			),
			array(
				'name'=>'کد ملی',
				'value'=>$model->national_code,
			),
			array(
				'name'=>'کد پستی',
				'value'=>$model->userDetails->zip_code,
			),
			array(
				'name'=>'آدرس',
				'value'=>$model->userDetails->address,
			),
			array(
				'name'=>'نوع کاربری',
				'value'=>$model->role->name,
			),
			array(
				'name'=>'وضعیت',
				'value'=>$model->statusLabels[$model->status],
			),
		),
	)); ?>