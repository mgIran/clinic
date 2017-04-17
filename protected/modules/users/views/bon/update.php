<?php
/* @var $this UserBonsManageController */
/* @var $model UserBons */

$this->breadcrumbs=array(
	'مدیریت بن های خرید'=>array('admin'),
	$model->title,
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن بن خرید', 'url'=>array('create')),
    array('label'=>'مدیریت بن های خرید', 'url'=>array('admin')),
);
?>

<h1>ویرایش بن خرید <?php echo $model->title; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>