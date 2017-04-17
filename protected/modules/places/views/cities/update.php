<?php
/* @var $this PlacesManageController */
/* @var $model Places */

$this->breadcrumbs=array(
	'مدیریت'=>array('admin'),
	$model->name,
	'ویرایش',
);

$this->menu=array(
	array('label'=>'افزودن', 'url'=>array('create')),
    array('label'=>'مدیریت', 'url'=>array('admin')),
);
?>

<h1>ویرایش شهر <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>