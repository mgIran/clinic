<?php
/* @var $this HolidaysMangeController */
/* @var $model Holidays */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
    array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>ویرایش عنوان روز <?php echo JalaliDate::date('d F Y', $model->date); ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>