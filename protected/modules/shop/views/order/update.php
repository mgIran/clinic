<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
    array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>ویرایش ShopOrder <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>