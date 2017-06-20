<?php
/* @var $this HolidaysMangeController */
/* @var $model Holidays */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	'افزودن',
);

$this->menu=array(
	array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>افزودن تعطیلات جدید</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>