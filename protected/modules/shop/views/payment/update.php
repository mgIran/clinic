<?php
/* @var $this ShopPaymentController */
/* @var $model ShopPaymentMethod */

$this->breadcrumbs=array(
	'مدیریت روش های پرداخت'=>array('admin'),
	$model->title=>array('view','id'=>$model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن روش پرداخت', 'url'=>array('create')),
    array('label'=>'مدیریت روش های پرداخت', 'url'=>array('admin')),
);
?>

<h1>ویرایش روش پرداخت "<?php echo $model->title; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>