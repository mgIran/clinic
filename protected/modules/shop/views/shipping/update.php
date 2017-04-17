<?php
/* @var $this ShopShippingController */
/* @var $model ShopShippingMethod */

$this->breadcrumbs=array(
	'مدیریت روش های تحویل'=>array('admin'),
	$model->title=>array('view','id'=>$model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن روش تحویل', 'url'=>array('create')),
    array('label'=>'مدیریت روش های تحویل', 'url'=>array('admin')),
);
?>

<h1>ویرایش روش تحویل "<?php echo $model->title; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>